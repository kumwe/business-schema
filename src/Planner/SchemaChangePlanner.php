<?php

declare(strict_types=1);

namespace Kumwe\BusinessSchema\Planner;

use Kumwe\BusinessDefinition\Domain\EntityTypeDefinition;
use Kumwe\BusinessDefinition\Domain\Expression;
use Kumwe\BusinessSchema\Domain\InvalidBusinessSchema;
use Kumwe\BusinessSchema\Domain\PhysicalColumnBlueprint;
use Kumwe\BusinessSchema\Domain\PhysicalForeignKeyBlueprint;
use Kumwe\BusinessSchema\Domain\PhysicalIndexBlueprint;
use Kumwe\BusinessSchema\Domain\PhysicalSchemaBlueprint;
use Kumwe\BusinessSchema\Domain\PhysicalTableBlueprint;
use Kumwe\BusinessSchema\Domain\SchemaOperation;
use Kumwe\BusinessSchema\Domain\SchemaOperationKind;
use Kumwe\BusinessSchema\Domain\SchemaEvolutionHints;
use Kumwe\BusinessSchema\Domain\SchemaPlan;
use Kumwe\BusinessSchema\Domain\SchemaPlanStatus;
use Kumwe\BusinessSchema\Domain\SchemaPlanStep;
use Kumwe\BusinessSchema\Domain\SchemaRisk;

/** Pure ordered schema difference compiler. Supplied snapshots are already authorized by the host. */
final class SchemaChangePlanner
{
    /**
     * @param array<string, PhysicalSchemaBlueprint> $dependencyBlueprints Pinned dependency schemas.
     * @return list<SchemaOperation> Deterministically ordered operations; never executes DDL.
     */
    public function operations(
        ?PhysicalSchemaBlueprint $prior,
        PhysicalSchemaBlueprint $target,
        EntityTypeDefinition $definition,
        array $dependencyBlueprints = [],
    ): array {
        if ($prior === null) {
            $tables = $target->tables();
            usort($tables, static function (PhysicalTableBlueprint $left, PhysicalTableBlueprint $right): int {
                if ($left->logicalName === 'record') {
                    return -1;
                }
                if ($right->logicalName === 'record') {
                    return 1;
                }
                return strcmp($left->logicalName, $right->logicalName);
            });
            $specifications = [];
            foreach ($tables as $table) {
                $withoutKeys = $this->withoutForeignKeys($table);
                $specifications[] = $this->spec(
                    SchemaOperationKind::CreateTable,
                    SchemaRisk::OnlineSafeAdditive,
                    $table->logicalName,
                    $table->logicalName,
                    null,
                    $withoutKeys->toArray(),
                    false,
                    'compensate_safe_addition',
                );
            }
            foreach ($tables as $table) {
                foreach ($table->foreignKeys() as $foreignKey) {
                    $specifications[] = $this->spec(
                        SchemaOperationKind::AddForeignKey,
                        SchemaRisk::BehaviorChanging,
                        $table->logicalName,
                        $foreignKey->logicalName,
                        null,
                        $foreignKey->toArray(),
                        false,
                        'resume_required',
                    );
                }
            }

            return $this->number($specifications);
        }

        $hints = SchemaEvolutionHints::fromDefinition($definition);
        $this->validateEvolutionHints($prior, $target, $definition, $hints);

        $oldTables = $this->tablesByLogical($prior);
        $newTables = $this->tablesByLogical($target);
        $drops = [];
        $creates = [];
        $alters = [];
        foreach (array_diff_key($oldTables, $newTables) as $logical => $table) {
            $drops[] = [
                SchemaOperationKind::DropTable,
                SchemaRisk::Destructive,
                $logical,
                $logical,
                $table->toArray(),
                null,
                false,
                'restore_required',
            ];
        }
        foreach (array_diff_key($newTables, $oldTables) as $logical => $table) {
            $creates[] = [
                SchemaOperationKind::CreateTable,
                SchemaRisk::OnlineSafeAdditive,
                $logical,
                $logical,
                null,
                $table->toArray(),
                false,
                'compensate_safe_addition',
            ];
        }
        foreach (array_intersect_key($newTables, $oldTables) as $logical => $table) {
            array_push($alters, ...$this->tableOperations(
                $oldTables[$logical],
                $table,
                $hints,
            ));
        }
        usort($drops, static fn (array $left, array $right): int => strcmp($left[2], $right[2]));
        usort($creates, static fn (array $left, array $right): int => strcmp($left[2], $right[2]));

        $repin = [];
        if ($hints->repin($definition->handle) !== null) {
            $repin[] = $this->spec(
                SchemaOperationKind::RepinRecords,
                SchemaRisk::BackfillRequired,
                'record',
                'definition_version',
                ['before_version' => $prior->definitionVersion],
                ['definition_version' => $definition->definitionVersion],
                true,
                'resume_required',
            );
        }

        return $this->number([...$drops, ...$creates, ...$alters, ...$repin]);
    }

    /**
     * Diff one table that both blueprints declare into the steps that reshape it.
     *
     * The work is emitted in three phases so that a constraint never stands in the way of the change
     * underneath it: every index and foreign key that is gone, altered, or attached to a column being
     * rewritten is dropped first, the column work then runs against the unconstrained table, and the
     * surviving constraints are recreated last. Two column changes are deliberately expanded rather than
     * attempted in place — a new non-null column becomes add-nullable, backfill, tighten, and a column
     * whose type changes is routed through a shadow column that is added, transformed into, then renamed
     * over the dropped original — so in both cases the row rewrite lands in a `Backfill` or `Transform`
     * step the executor can run in bounded chunks. Tightening an existing column to non-null likewise gets
     * its backfill first.
     *
     * @param   PhysicalTableBlueprint  $prior   The table as the installation records it.
     * @param   PhysicalTableBlueprint  $target  The same logical table as the published version compiles it.
     * @param   SchemaEvolutionHints    $hints   Renames, backfills, and transforms the published version declares.
     *
     * @return  list<array{
     *            SchemaOperationKind,
     *            SchemaRisk,
     *            string,
     *            string,
     *            array<string, mixed>|null,
     *            array<string, mixed>|null,
     *            bool,
     *            string
     *          }>  Unnumbered operation specifications, in the order they must run.
     *
     * @throws  InvalidBusinessSchema  When a declared rename names a column missing from either side, a
     *          column changes type with no transform declared for it, a transform or backfill expression
     *          reads a field the prior table does not have, or a non-null column has neither a canonical
     *          default nor a declared backfill.
     *
     * @since   2.0.0
     */
    private function tableOperations(
        PhysicalTableBlueprint $prior,
        PhysicalTableBlueprint $target,
        SchemaEvolutionHints $hints,
    ): array {
        $dropConstraints = [];
        $columnWork = [];
        $addConstraints = [];
        $oldForeignKeys = $this->foreignKeysByLogical($prior);
        $newForeignKeys = $this->foreignKeysByLogical($target);
        $oldColumnsForTransform = $this->columnsByLogical($prior);
        $newColumnsForTransform = $this->columnsByLogical($target);
        $transformedPhysical = [];
        foreach ($hints->transforms() as $logical => $_expression) {
            if (
                $prior->logicalName === 'record'
                && isset($oldColumnsForTransform[$logical], $newColumnsForTransform[$logical])
                && $oldColumnsForTransform[$logical]->doctrineType !== $newColumnsForTransform[$logical]->doctrineType
            ) {
                $transformedPhysical[] = $oldColumnsForTransform[$logical]->physicalName;
            }
        }
        foreach ($oldForeignKeys as $logical => $key) {
            if (
                !isset($newForeignKeys[$logical])
                || $newForeignKeys[$logical]->toArray() !== $key->toArray()
                || array_intersect($key->localColumns, $transformedPhysical) !== []
            ) {
                $dropConstraints[] = $this->spec(
                    SchemaOperationKind::DropForeignKey,
                    SchemaRisk::BehaviorChanging,
                    $prior->logicalName,
                    $logical,
                    $key->toArray(),
                    null,
                    false,
                    'resume_required',
                );
            }
        }
        $oldIndexes = $this->indexesByLogical($prior);
        $newIndexes = $this->indexesByLogical($target);
        foreach ($oldIndexes as $logical => $index) {
            if (
                !isset($newIndexes[$logical])
                || $newIndexes[$logical]->toArray() !== $index->toArray()
                || array_intersect($index->columns, $transformedPhysical) !== []
            ) {
                $dropConstraints[] = $this->spec(
                    SchemaOperationKind::DropIndex,
                    SchemaRisk::RebuildOrLocking,
                    $prior->logicalName,
                    $logical,
                    $index->toArray(),
                    null,
                    false,
                    'resume_required',
                );
            }
        }

        $oldColumns = $this->columnsByLogical($prior);
        $newColumns = $this->columnsByLogical($target);
        $renamedOld = $hints->renameForTable($prior->logicalName);
        foreach ($renamedOld as $oldLogical => $newLogical) {
            if (!isset($oldColumns[$oldLogical], $newColumns[$newLogical])) {
                throw new InvalidBusinessSchema('A declared schema rename does not match compiled columns.');
            }
            $columnWork[] = $this->spec(
                SchemaOperationKind::RenameColumn,
                SchemaRisk::BehaviorChanging,
                $prior->logicalName,
                $newLogical,
                $oldColumns[$oldLogical]->toArray(),
                $newColumns[$newLogical]->toArray(),
                false,
                'resume_required',
            );
            unset($oldColumns[$oldLogical], $newColumns[$newLogical]);
        }
        foreach (array_diff_key($oldColumns, $newColumns) as $logical => $column) {
            $columnWork[] = $this->spec(
                SchemaOperationKind::DropColumn,
                SchemaRisk::Destructive,
                $prior->logicalName,
                $logical,
                $column->toArray(),
                null,
                false,
                'restore_required',
            );
        }
        foreach (array_diff_key($newColumns, $oldColumns) as $logical => $column) {
            if ($column->nullable) {
                $columnWork[] = $this->spec(
                    SchemaOperationKind::AddColumn,
                    SchemaRisk::OnlineSafeAdditive,
                    $target->logicalName,
                    $logical,
                    null,
                    $column->toArray(),
                    false,
                    'compensate_safe_addition',
                );
                continue;
            }
            $value = $this->backfillValueOrDefault($column, $hints, $logical);
            $nullable = new PhysicalColumnBlueprint(
                $column->logicalName,
                $column->physicalName,
                $column->doctrineType,
                $column->options,
                true,
            );
            $columnWork[] = $this->spec(
                SchemaOperationKind::AddColumn,
                SchemaRisk::OnlineSafeAdditive,
                $target->logicalName,
                $logical,
                null,
                $nullable->toArray(),
                false,
                'compensate_safe_addition',
            );
            $columnWork[] = $this->spec(
                SchemaOperationKind::Backfill,
                SchemaRisk::BackfillRequired,
                $target->logicalName,
                $logical,
                null,
                $this->backfillState($nullable, $value, $oldColumns),
                true,
                'resume_required',
            );
            $columnWork[] = $this->spec(
                SchemaOperationKind::AlterColumn,
                SchemaRisk::BehaviorChanging,
                $target->logicalName,
                $logical,
                $nullable->toArray(),
                $column->toArray(),
                false,
                'resume_required',
            );
        }
        foreach (array_intersect_key($newColumns, $oldColumns) as $logical => $column) {
            $old = $oldColumns[$logical];
            if ($old->toArray() === $column->toArray()) {
                continue;
            }
            if ($old->doctrineType !== $column->doctrineType) {
                $expression = $hints->transform($logical)
                    ?? throw new InvalidBusinessSchema(
                        'A physical type change requires an explicit bounded transform expression.',
                    );
                $shadow = $this->transformShadowColumn($target, $column);
                $dependencies = [];
                foreach ($expression->dependencies() as $dependency) {
                    $dependencyColumn = $oldColumns[$dependency] ?? null;
                    if ($dependencyColumn === null) {
                        throw new InvalidBusinessSchema(
                            'A schema transform references a field unavailable in the prior physical table.',
                        );
                    }
                    $dependencies[$dependency] = $dependencyColumn->toArray();
                }
                ksort($dependencies, SORT_STRING);
                $columnWork[] = $this->spec(
                    SchemaOperationKind::AddColumn,
                    SchemaRisk::OnlineSafeAdditive,
                    $target->logicalName,
                    $logical . '.transform',
                    null,
                    $shadow->toArray(),
                    false,
                    'compensate_safe_addition',
                );
                $columnWork[] = $this->spec(
                    SchemaOperationKind::Transform,
                    SchemaRisk::RebuildOrLocking,
                    $target->logicalName,
                    $logical . '.transform',
                    $old->toArray(),
                    [
                        'source' => $old->toArray(),
                        'target' => $shadow->toArray(),
                        'expression' => $expression->toArray(),
                        'dependencies' => $dependencies,
                        'primary_key' => $prior->primaryKey,
                    ],
                    true,
                    'resume_required',
                );
                $columnWork[] = $this->spec(
                    SchemaOperationKind::DropColumn,
                    SchemaRisk::RebuildOrLocking,
                    $target->logicalName,
                    $logical,
                    $old->toArray(),
                    null,
                    false,
                    'resume_required',
                );
                $columnWork[] = $this->spec(
                    SchemaOperationKind::RenameColumn,
                    SchemaRisk::RebuildOrLocking,
                    $target->logicalName,
                    $logical,
                    $shadow->toArray(),
                    $column->toArray(),
                    false,
                    'resume_required',
                );
                continue;
            }
            if ($old->nullable && !$column->nullable) {
                $value = $this->backfillValueOrDefault($column, $hints, $logical);
                $columnWork[] = $this->spec(
                    SchemaOperationKind::Backfill,
                    SchemaRisk::BackfillRequired,
                    $target->logicalName,
                    $logical,
                    null,
                    $this->backfillState($old, $value, $oldColumns),
                    true,
                    'resume_required',
                );
            }
            $columnWork[] = $this->spec(
                SchemaOperationKind::AlterColumn,
                SchemaRisk::BehaviorChanging,
                $target->logicalName,
                $logical,
                $old->toArray(),
                $column->toArray(),
                false,
                'resume_required',
            );
        }

        foreach ($newIndexes as $logical => $index) {
            if (
                !isset($oldIndexes[$logical])
                || $oldIndexes[$logical]->toArray() !== $index->toArray()
                || array_intersect($index->columns, $transformedPhysical) !== []
            ) {
                $addConstraints[] = $this->spec(
                    SchemaOperationKind::AddIndex,
                    SchemaRisk::RebuildOrLocking,
                    $target->logicalName,
                    $logical,
                    null,
                    $index->toArray(),
                    false,
                    'resume_required',
                );
            }
        }
        foreach ($newForeignKeys as $logical => $key) {
            if (
                !isset($oldForeignKeys[$logical])
                || $oldForeignKeys[$logical]->toArray() !== $key->toArray()
                || array_intersect($key->localColumns, $transformedPhysical) !== []
            ) {
                $addConstraints[] = $this->spec(
                    SchemaOperationKind::AddForeignKey,
                    SchemaRisk::BehaviorChanging,
                    $target->logicalName,
                    $logical,
                    null,
                    $key->toArray(),
                    false,
                    'resume_required',
                );
            }
        }

        return [...$dropConstraints, ...$columnWork, ...$addConstraints];
    }

    /**
     * Turn collected specifications into the numbered operations a plan is allowed to contain.
     *
     * Ordinals come from array position, so the order in which the callers concatenated their
     * specifications is exactly the order the executor will apply them; a plan requires those ordinals to
     * be contiguous from one, which is why numbering happens once, here, after all sorting is done.
     *
     * @param   list<array{
     *            SchemaOperationKind,
     *            SchemaRisk,
     *            string,
     *            string,
     *            array<string, mixed>|null,
     *            array<string, mixed>|null,
     *            bool,
     *            string
     *          }> $specs  Specifications already in execution order.
     *
     * @return  list<SchemaOperation>  The same steps, numbered from one.
     *
     * @since   2.0.0
     */
    private function number(array $specs): array
    {
        $operations = [];
        foreach ($specs as $offset => $spec) {
            $operations[] = new SchemaOperation($offset + 1, ...$spec);
        }

        return $operations;
    }

    /**
     * Copy a table with its referential constraints stripped off.
     *
     * A first install creates every table this way and adds the foreign keys in a later pass, so a table
     * may be created before the table it points at exists.
     *
     * @param   PhysicalTableBlueprint  $table  Compiled table whose constraints are being deferred.
     *
     * @return  PhysicalTableBlueprint  The same columns, primary key, indexes, and options, with no
     *          foreign keys.
     *
     * @since   2.0.0
     */
    private function withoutForeignKeys(PhysicalTableBlueprint $table): PhysicalTableBlueprint
    {
        return new PhysicalTableBlueprint(
            $table->logicalName,
            $table->physicalName,
            $table->kind,
            $table->columns(),
            $table->primaryKey,
            $table->indexes(),
            [],
            $table->options,
        );
    }

    /**
     * Report whether the plan would change structure that rows on an older definition version still rely on.
     *
     * Dropping a table or a column, renaming a column, and rewriting one through a transform all qualify
     * outright. An alter-column step qualifies too unless it is a pure relaxation, which is the one shape
     * of change that leaves a row written against the older version readable exactly as it stands.
     *
     * @param   list<SchemaOperation>  $operations  Steps derived for the plan.
     *
     * @return  bool  True when at least one step would invalidate a row still pinned to an older version.
     *
     * @since   2.0.0
     */
    public function containsPinnedRowBreakingChange(array $operations): bool
    {
        foreach ($operations as $operation) {
            if (
                in_array(
                    $operation->kind,
                    [
                    SchemaOperationKind::DropTable,
                    SchemaOperationKind::DropColumn,
                    SchemaOperationKind::Transform,
                    SchemaOperationKind::RenameColumn,
                    ],
                    true,
                )
            ) {
                return true;
            }
            if (
                $operation->kind === SchemaOperationKind::AlterColumn
                && !$this->additiveColumnRelaxation($operation)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Report whether the plan re-pins its records onto the published version and leaves nothing behind.
     *
     * A re-pin only counts when it lands on exactly the version being published, since a step aiming at any
     * other version would leave rows the plan is about to break unmigrated. Two shapes disqualify the plan
     * whatever the re-pin says: a table drop, which no re-pin can carry rows through, and a column drop
     * that is not paired with the transform writing its replacement.
     *
     * @param   list<SchemaOperation>  $operations     Steps derived for the plan.
     * @param   int                    $targetVersion  Definition version records must end up pinned to.
     *
     * @return  bool  True only when a matching re-pin is present and every drop in the plan is covered.
     *
     * @since   2.0.0
     */
    public function hasRecordRepin(array $operations, int $targetVersion): bool
    {
        $repin = false;
        foreach ($operations as $operation) {
            if (
                $operation->kind === SchemaOperationKind::RepinRecords
                && ($operation->after['definition_version'] ?? null) === $targetVersion
            ) {
                $repin = true;
            }
            if ($operation->kind === SchemaOperationKind::DropTable) {
                return false;
            }
            if ($operation->kind === SchemaOperationKind::DropColumn) {
                $transform = false;
                foreach ($operations as $candidate) {
                    if (
                        $candidate->kind === SchemaOperationKind::Transform
                        && $candidate->subject === $operation->subject . '.transform'
                    ) {
                        $transform = true;
                        break;
                    }
                }
                if (!$transform) {
                    return false;
                }
            }
        }

        return $repin;
    }

    /**
     * Decide whether an alter-column step only loosens the column, leaving stored values readable as they are.
     *
     * This is the exception that keeps ordinary widening out of the pinned-row guard. Logical name, physical
     * name, and Doctrine type must be untouched, nullability may only relax, a declared length or precision
     * may only grow, and every remaining portable option must be identical. The `default` option is excluded
     * from the comparison because it governs rows written from now on, not rows already stored. A step
     * missing either state is not treated as a relaxation.
     *
     * @param   SchemaOperation  $operation  Alter-column step to classify.
     *
     * @return  bool  True when the change cannot invalidate a row pinned to an older definition version.
     *
     * @since   2.0.0
     */
    private function additiveColumnRelaxation(SchemaOperation $operation): bool
    {
        if ($operation->before === null || $operation->after === null) {
            return false;
        }
        $before = PhysicalColumnBlueprint::fromArray($operation->before);
        $after = PhysicalColumnBlueprint::fromArray($operation->after);
        if (
            $before->logicalName !== $after->logicalName
            || $before->physicalName !== $after->physicalName
            || $before->doctrineType !== $after->doctrineType
            || ($before->nullable && !$after->nullable)
        ) {
            return false;
        }
        $oldOptions = $before->options;
        $newOptions = $after->options;
        unset($oldOptions['default'], $newOptions['default']);
        $oldLength = $oldOptions['length'] ?? null;
        $newLength = $newOptions['length'] ?? null;
        if (is_int($oldLength) && (!is_int($newLength) || $newLength < $oldLength)) {
            return false;
        }
        $oldPrecision = $oldOptions['precision'] ?? null;
        $newPrecision = $newOptions['precision'] ?? null;
        if (is_int($oldPrecision) && (!is_int($newPrecision) || $newPrecision < $oldPrecision)) {
            return false;
        }
        unset($oldOptions['length'], $newOptions['length'], $oldOptions['precision'], $newOptions['precision']);

        return $oldOptions === $newOptions;
    }

    /**
     * List the definition handles this definition points at, from its relationships and reference fields.
     *
     * Fields contribute a handle only when they are `core.entity_reference` or `core.ordered_lines` and
     * name a string target. The result is deduplicated and sorted, so dependencies are always resolved in
     * the same order however the definition happened to be written.
     * Hosts resolve these handles under their own site and version authority before compiling the
     * dependency blueprints supplied to operations(); this method performs no lookup or persistence.
     *
     * @param   EntityTypeDefinition  $definition  Definition version whose outgoing references are wanted.
     *
     * @return  list<string>  Referenced handles in ascending order, each once; empty when it references none.
     *
     * @since   2.0.0
     */
    public function dependencyHandles(EntityTypeDefinition $definition): array
    {
        $handles = [];
        foreach ($definition->relationships() as $relationship) {
            $handles[] = $relationship->target;
        }
        foreach ($definition->fields() as $field) {
            if (!in_array($field->type, ['core.entity_reference', 'core.ordered_lines'], true)) {
                continue;
            }
            $target = $field->configuration['target'] ?? null;
            if (is_string($target)) {
                $handles[] = $target;
            }
        }
        $handles = array_values(array_unique($handles));
        sort($handles, SORT_STRING);

        return $handles;
    }

    /**
     * Compile the physical schema of every definition this one references.
     *
     * A handle the definition re-pins is resolved at exactly the version that re-pin names, so the
     * dependency is read as the plan intends to leave it; every other handle is taken at whatever version
     * the site publishes now. A referenced handle that resolves to nothing stops planning rather than
     * yielding a plan derived from a partial graph.
     *
     * @param   EntityTypeDefinition  $definition  Definition version whose references are being resolved.
     * @param   SiteContext           $site        Site the referenced definitions must be published on.
     *
     * @return  list<PhysicalSchemaBlueprint>  One blueprint per referenced handle, in handle order; empty
     *          when the definition references none.
     *
     * @throws  BusinessSchemaNotFound  When a referenced handle has no published version on this site, or
     *          none at the version a re-pin names.
     * @throws  InvalidBusinessSchema  When the definition's compatibility metadata is malformed, a
     *          referenced handle is not a namespaced definition handle, or a dependency fails to compile.
     *
     * @since   2.0.0
     */


    /**
     * Package one step's arguments in the positional order `SchemaOperation` takes them after its ordinal.
     *
     * Specifications are collected, sorted, and concatenated before anything is numbered, so this stops
     * short of constructing the operation; `number()` supplies the ordinal once the final order is settled.
     *
     * @param   SchemaOperationKind        $kind              Semantic change the gateway must realise.
     * @param   SchemaRisk                 $risk              Impact class this step contributes to the plan.
     * @param   string                     $table             Logical table the step acts on.
     * @param   string                     $subject           Logical object within that table, such as a column
     *          or constraint name.
     * @param   array<string, mixed>|null  $before            State of the subject before the step, or null when
     *          the step only adds.
     * @param   array<string, mixed>|null  $after             State the subject must reach, or null when the step
     *          only removes.
     * @param   bool                       $requiresBackfill  Whether the step rewrites rows rather than shape.
     * @param   string                     $recovery          Recovery implication an interrupted run leaves.
     *
     * @return  array{
     *            SchemaOperationKind,
     *            SchemaRisk,
     *            string,
     *            string,
     *            array<string, mixed>|null,
     *            array<string, mixed>|null,
     *            bool,
     *            string
     *          }  The arguments in constructor order, ready to be spread after an ordinal.
     *
     * @since   2.0.0
     */
    private function spec(
        SchemaOperationKind $kind,
        SchemaRisk $risk,
        string $table,
        string $subject,
        ?array $before,
        ?array $after,
        bool $requiresBackfill,
        string $recovery,
    ): array {
        return [$kind, $risk, $table, $subject, $before, $after, $requiresBackfill, $recovery];
    }

    /**
     * Index a schema's tables by the logical name plan operations address them with.
     *
     * The diff is expressed as `array_diff_key` and `array_intersect_key` over two of these maps, so the
     * keys have to be the logical names; sorting them keeps the resulting drops, creations, and
     * alterations in a stable order.
     *
     * @param   PhysicalSchemaBlueprint  $blueprint  Schema whose tables are being indexed.
     *
     * @return  array<string, PhysicalTableBlueprint>  Tables keyed by logical name, sorted by key.
     *
     * @since   2.0.0
     */
    private function tablesByLogical(PhysicalSchemaBlueprint $blueprint): array
    {
        $result = [];
        foreach ($blueprint->tables() as $table) {
            $result[$table->logicalName] = $table;
        }
        ksort($result, SORT_STRING);

        return $result;
    }

    /**
     * Index a table's columns by logical name, which is the identity the whole column diff is keyed on.
     *
     * Additions, removals, declared renames, and retypes are all resolved against logical names, so this is
     * the map both sides are compared through; sorting keeps the emitted steps in a stable order.
     *
     * @param   PhysicalTableBlueprint  $table  Table whose columns are being indexed.
     *
     * @return  array<string, PhysicalColumnBlueprint>  Columns keyed by logical name, sorted by key.
     *
     * @since   2.0.0
     */
    private function columnsByLogical(PhysicalTableBlueprint $table): array
    {
        $result = [];
        foreach ($table->columns() as $column) {
            $result[$column->logicalName] = $column;
        }
        ksort($result, SORT_STRING);

        return $result;
    }

    /**
     * Index a table's indexes and unique constraints by logical name.
     *
     * @param   PhysicalTableBlueprint  $table  Table whose indexes are being indexed.
     *
     * @return  array<string, PhysicalIndexBlueprint>  Indexes keyed by logical name, sorted by key.
     *
     * @since   2.0.0
     */
    private function indexesByLogical(PhysicalTableBlueprint $table): array
    {
        $result = [];
        foreach ($table->indexes() as $index) {
            $result[$index->logicalName] = $index;
        }
        ksort($result, SORT_STRING);

        return $result;
    }

    /**
     * Index a table's outgoing referential constraints by logical name.
     *
     * @param   PhysicalTableBlueprint  $table  Table whose foreign keys are being indexed.
     *
     * @return  array<string, PhysicalForeignKeyBlueprint>  Constraints keyed by logical name, sorted by key.
     *
     * @since   2.0.0
     */
    private function foreignKeysByLogical(PhysicalTableBlueprint $table): array
    {
        $result = [];
        foreach ($table->foreignKeys() as $key) {
            $result[$key->logicalName] = $key;
        }
        ksort($result, SORT_STRING);

        return $result;
    }

    /**
     * Read the value the published version declares for filling a column existing rows do not have.
     *
     * Absence is a planning failure rather than a licence to guess: a required column with no declared
     * backfill could only be filled with a value the definition never approved, so planning stops here
     * rather than handing an unfillable step to the executor.
     *
     * @param   SchemaEvolutionHints  $hints          Evolution hints the published version declares.
     * @param   string                $logicalColumn  Logical column the backfill is keyed under.
     *
     * @return  bool|int|string|Expression  The declared literal, or the bounded expression to evaluate per row.
     *
     * @throws  InvalidBusinessSchema  When the column name is not a metadata identifier, no backfill is
     *          declared for it, or the declared value is not an exact scalar or expression.
     *
     * @since   2.0.0
     */
    private function backfillValue(
        SchemaEvolutionHints $hints,
        string $logicalColumn,
    ): bool|int|string|Expression {
        if (!$hints->hasBackfill($logicalColumn)) {
            throw new InvalidBusinessSchema(sprintf(
                'Non-null column %s requires canonical compatibility_metadata.backfills data.',
                $logicalColumn,
            ));
        }
        $value = $hints->backfill($logicalColumn);
        if (!is_bool($value) && !is_int($value) && !is_string($value) && !$value instanceof Expression) {
            throw new InvalidBusinessSchema('A validated schema backfill value became unavailable.');
        }

        return $value;
    }

    /**
     * Choose what a newly required column is filled with, preferring the column's own compiled default.
     *
     * A column that declares a default already says what a row without a value should hold, so the
     * definition only has to declare a backfill for columns that do not.
     *
     * @param   PhysicalColumnBlueprint  $column         Column being added as, or tightened to, non-null.
     * @param   SchemaEvolutionHints     $hints          Evolution hints consulted when there is no default.
     * @param   string                   $logicalColumn  Logical column a declared backfill is keyed under.
     *
     * @return  bool|int|string|Expression  The column's default, or the declared literal or expression.
     *
     * @throws  InvalidBusinessSchema  When the compiled default is not an exact scalar, or the column has no
     *          default and the version declares no usable backfill for it.
     *
     * @since   2.0.0
     */
    private function backfillValueOrDefault(
        PhysicalColumnBlueprint $column,
        SchemaEvolutionHints $hints,
        string $logicalColumn,
    ): bool|int|string|Expression {
        if (!array_key_exists('default', $column->options)) {
            return $this->backfillValue($hints, $logicalColumn);
        }
        $value = $column->options['default'];
        if (!is_bool($value) && !is_int($value) && !is_string($value)) {
            throw new InvalidBusinessSchema('A non-null schema column has an invalid canonical default.');
        }

        return $value;
    }

    /**
     * Assemble the target state a backfill step carries, resolving an expression's inputs to real columns.
     *
     * A literal needs nothing but the column and the value. An expression additionally has to travel with
     * the physical shape of every column it reads, because the gateway builds its statement from this state
     * alone and never re-reads the definition; the dependencies are sorted so the step checksums the same
     * way each time it is derived.
     *
     * @param   PhysicalColumnBlueprint                 $column            Column being filled, in the nullable
     *          shape it holds while the fill runs.
     * @param   bool|int|string|Expression              $value             Literal to write, or the expression
     *          evaluated per row.
     * @param   array<string, PhysicalColumnBlueprint>  $availableColumns  Columns of the source table an
     *          expression may read, keyed by logical name.
     *
     * @return  array<string, mixed>  `column` with `value`, or `column` with `expression` and `dependencies`.
     *
     * @throws  InvalidBusinessSchema  When the expression reads a field the source table does not carry.
     *
     * @since   2.0.0
     */
    private function backfillState(
        PhysicalColumnBlueprint $column,
        bool|int|string|Expression $value,
        array $availableColumns,
    ): array {
        $state = ['column' => $column->toArray()];
        if (!$value instanceof Expression) {
            return [...$state, 'value' => $value];
        }
        $dependencies = [];
        foreach ($value->dependencies() as $logical) {
            $dependency = $availableColumns[$logical] ?? null;
            if ($dependency === null) {
                throw new InvalidBusinessSchema(
                    'A schema backfill Expression references a field unavailable in the source table.',
                );
            }
            $dependencies[$logical] = $dependency->toArray();
        }
        ksort($dependencies, SORT_STRING);

        return [
            ...$state,
            'expression' => $value->toArray(),
            'dependencies' => $dependencies,
        ];
    }

    /**
     * Derive the temporary column a type change converts its values into before taking the real name.
     *
     * The physical name is an `x_` prefix over a digest of the table and target column names, so the same
     * change derives the same name on every compilation, and the plan checksum an approver was bound to
     * survives the executor recompiling the blueprint before it runs. The shadow is always nullable, since
     * it holds nothing until the chunked transform fills it.
     *
     * @param   PhysicalTableBlueprint   $table   Table the column lives on, mixed into the generated name.
     * @param   PhysicalColumnBlueprint  $target  Column in its target shape, supplying the new type and options.
     *
     * @return  PhysicalColumnBlueprint  A nullable column logically named `<column>.transform`.
     *
     * @since   2.0.0
     */
    private function transformShadowColumn(
        PhysicalTableBlueprint $table,
        PhysicalColumnBlueprint $target,
    ): PhysicalColumnBlueprint {
        $physical = 'x_' . substr(hash(
            'sha256',
            $table->physicalName . "\0" . $target->physicalName . "\0transform",
        ), 0, 40);

        return new PhysicalColumnBlueprint(
            $target->logicalName . '.transform',
            $physical,
            $target->doctrineType,
            $target->options,
            true,
        );
    }

    /**
     * Prove every declared evolution hint corresponds to a change this evolution actually makes.
     *
     * A hint that matches nothing is refused rather than ignored, because it was written to authorize a
     * rewrite and quietly dropping it would let the plan run without one. A repin must name either this
     * definition's own newly published version — never a first version, which has no older rows — or a
     * handle the definition genuinely depends on. A rename must name columns present on both sides. A
     * transform must sit on a record column whose type really changes, and a backfill on a record column
     * that is new or newly non-null.
     *
     * @param   PhysicalSchemaBlueprint  $prior       Blueprint recorded as installed.
     * @param   PhysicalSchemaBlueprint  $target      Blueprint the published version installs.
     * @param   EntityTypeDefinition     $definition  Published version whose hints are being proved.
     * @param   SchemaEvolutionHints     $hints       Hints already parsed from that version's metadata.
     *
     * @return  void
     *
     * @throws  InvalidBusinessSchema  When a repin targets a version other than the one being published,
     *          or a handle that is not a declared dependency; when a rename names a table or column absent
     *          from either blueprint; when a transform names a record column whose type is unchanged; or
     *          when a backfill names a record column that is neither new nor newly non-null.
     *
     * @since   2.0.0
     */
    private function validateEvolutionHints(
        PhysicalSchemaBlueprint $prior,
        PhysicalSchemaBlueprint $target,
        EntityTypeDefinition $definition,
        SchemaEvolutionHints $hints,
    ): void {
        $dependencies = $this->dependencyHandles($definition);
        foreach ($hints->repins() as $handle => $version) {
            if ($handle === $definition->handle) {
                if ($version !== $definition->definitionVersion || $definition->definitionVersion < 2) {
                    throw new InvalidBusinessSchema(
                        'A self record repin must target the exact newly published definition version.',
                    );
                }
                continue;
            }
            if (!in_array($handle, $dependencies, true)) {
                throw new InvalidBusinessSchema('A schema repin targets an undeclared definition dependency.');
            }
        }
        foreach ($hints->renames() as $tableLogical => $renames) {
            $oldTable = $prior->table($tableLogical);
            $newTable = $target->table($tableLogical);
            if ($oldTable === null || $newTable === null) {
                throw new InvalidBusinessSchema('A schema rename targets a table unavailable in this evolution.');
            }
            foreach ($renames as $old => $new) {
                if ($oldTable->column($old) === null || $newTable->column($new) === null) {
                    throw new InvalidBusinessSchema('A schema rename does not match prior and target columns.');
                }
            }
        }
        $oldRecord = $prior->table('record');
        $newRecord = $target->table('record');
        foreach ($hints->transforms() as $logical => $_expression) {
            $old = $oldRecord?->column($logical);
            $new = $newRecord?->column($logical);
            if ($old === null || $new === null || $old->doctrineType === $new->doctrineType) {
                throw new InvalidBusinessSchema(
                    'A schema transform must correspond to one explicit record-column type change.',
                );
            }
        }
        foreach ($hints->backfills() as $logical => $_literal) {
            $old = $oldRecord?->column($logical);
            $new = $newRecord?->column($logical);
            if ($new === null || $new->nullable || ($old !== null && !$old->nullable)) {
                throw new InvalidBusinessSchema(
                    'A schema backfill must correspond to one added or newly non-null record column.',
                );
            }
        }
    }
}
