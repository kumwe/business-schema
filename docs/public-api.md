# Public API

Constructor invariants, serialization, exceptions and method contracts follow. Values perform no I/O; host inputs must remain stable through each operation.

## Kumwe\BusinessSchema\ConfigProvider

/** Deterministic service declarations; trusted lookup and naming configuration are explicit host inputs. */

### __invoke

/**
     * @return array{dependencies: array{factories: array<class-string, class-string>, shared: array<class-string,
     * bool>}}
     */

## Kumwe\BusinessSchema\Compiler\CanonicalDefinitionPhysicalSchemaCompiler

/**
 * Compiles immutable definition metadata into a portable, canonical physical blueprint.
 *
 * This is the only implementation of the compiler port, and it is what makes a schema plan bindable: one
 * published definition compiled twice in the same site yields the same tables, columns, indexes and keys,
 * so the planner, the approving operator and the executor can compare checksums instead of structures.
 * Determinism is bought deliberately. Fields and relationships are walked in handle order, every emitted
 * collection is sorted before a blueprint sees it, and physical identifiers come from
 * `PhysicalNameCompiler` rather than from anything positional.
 *
 * What it emits stays inside the portable Doctrine subset the supported engines agree on: one record
 * table per definition, a junction or line table for each collection relationship this side has to
 * materialize, a line table for each `core.ordered_lines` field, tenancy columns leading every generated
 * index so a unique field is unique within its scope, and a covering index behind every foreign key so
 * introspection reads alike everywhere. Relationship and reference targets are resolved through the
 * definition catalog at the version the source's evolution hints pin, never at whatever happens to be
 * published at the moment of compilation.
 *
 * @since  2.0.0
 */

### __construct

/**
     * Wire the compiler to the catalog, field-type resolver and name compiler it reads through.
     *
     * @param  DefinitionSchemaLookup  $definitions  Catalog every relationship and reference target
     *         is resolved through, within the site being compiled.
     * @param  FieldTypeDefinitionResolver   $fieldTypes   Resolver consulted for the storage kind of a
     *         field type this compiler has no native mapping for.
     * @param  PhysicalNameCompiler          $names        Compiler turning logical handles into the
     *         prefixed, length-bounded identifiers that are installed.
     *
     * @since  2.0.0
     */

### compile

/**
     * Compile every physical table one published definition version installs.
     *
     * The definition's own record table is always emitted. A collection relationship this side has to
     * materialize adds a junction or line table, an ordered-line field adds a line table, and a singular
     * relationship adds nothing here because it lives as a column on the record table instead. Tables are
     * ordered by logical name before they are handed over, so the checksum an operator approved does not
     * move because a later edit rearranged the definition's declarations.
     *
     * @param   EntityTypeDefinition  $definition  Published definition version to compile; a draft is
     *          refused, as is one belonging to another site.
     * @param   string           $site        Site owning the definition, and the site every
     *          relationship and reference target is resolved in.
     *
     * @return  PhysicalSchemaBlueprint  The tables, carrying the definition's version and checksum so a
     *          later stage can prove it is looking at the same bytes.
     *
     * @throws  InvalidBusinessSchema  When the definition belongs to another site or has no published
     *          version, an ordered-line field declares no string target, a target definition is
     *          unavailable or not published at the pinned version, or a compiled table breaks a
     *          physical-schema rule.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When a field names a type
     *          the resolver cannot produce, or the definition's own canonical document cannot be encoded.
     *
     * @since   2.0.0
     */

## Kumwe\BusinessSchema\Domain\SchemaPlanApproval

/**
 * Durable evidence that a named actor approved one exact canonical schema plan.
 *
 * Approval is the boundary between compiling a plan and running DDL, so the evidence has to name the plan
 * by content rather than by identifier: `SchemaPlan` refuses to hold an approval whose checksum is not the
 * checksum of its own operations, which makes an approval useless if the plan is re-planned afterwards.
 * High-impact work additionally carries the confirmation digest produced by the operator's step-up.
 *
 * @since  2.0.0
 */

### __construct

/**
     * Capture who approved which canonical plan, when, and under what confirmation.
     *
     * @param   string             $actorIdentifier     Bounded identity of the approving administrator.
     * @param   DateTimeImmutable  $approvedAt          Instant the approval was granted, kept in UTC.
     * @param   string             $approvedChecksum    SHA-256 of the canonical plan this approval binds to.
     * @param   string|null        $confirmationDigest  Step-up confirmation for high-impact work, else null.
     *
     * @throws  InvalidBusinessSchema  When the actor is empty, too long, or holds control characters, or a
     *          supplied checksum or digest is not a lowercase SHA-256 value.
     *
     * @since   2.0.0
     */

### fromArray

/**
     * Rebuild an approval from its persisted document.
     *
     * @param   array<string, mixed>  $document  Stored approval object, as it appears inside a plan document.
     *
     * @return  self  The revalidated approval, with its timestamp normalized to UTC.
     *
     * @throws  InvalidBusinessSchema  When the document carries an unknown property, a required field is
     *          missing or misshapen, or the timestamp cannot be parsed.
     *
     * @since   2.0.0
     */

### toArray

/**
     * Export the approval in the shape persisted inside a plan document.
     *
     * @return  array<string, ?string>  Approval fields keyed as stored; `confirmation_digest` is null when
     *          the approved plan needed no step-up confirmation.
     *
     * @since   2.0.0
     */

## Kumwe\BusinessSchema\Domain\SchemaInstallation

/**
 * The physical schema one business definition currently has installed on one site.
 *
 * This is the record the runtime trusts: the record repositories resolve a definition to its installation
 * before touching a table, and refuse to run unless the status admits it. Construction re-proves that the
 * stored blueprint really is the one the recorded definition version and checksums describe, so an
 * installation row cannot drift away from the schema it claims to represent without being rejected on load.
 * Status changes are made by returning a new installation, never by mutating this one.
 *
 * @since  2.0.0
 */

### __construct

/**
     * Record an installed schema together with the evidence that binds it to its definition.
     *
     * @param   string                    $definitionId        UUID of the installed business definition.
     * @param   string                    $siteIdentifier      Site whose tables this installation owns.
     * @param   string                    $ownerIdentifier     `core`, an extension handle, or `vendor/package`.
     * @param   int                       $definitionVersion   Published version whose shape is installed.
     * @param   string                    $definitionChecksum  SHA-256 of that published definition.
     * @param   string                    $schemaChecksum      SHA-256 of the blueprint actually installed.
     * @param   PhysicalSchemaBlueprint   $blueprint           Tables as they are expected to exist right now.
     * @param   SchemaInstallationStatus  $status              Whether record traffic may use these tables.
     * @param   DateTimeImmutable         $installedAt         Instant the schema was first installed.
     * @param   DateTimeImmutable         $updatedAt           Instant of the latest status or schema change.
     *
     * @throws  InvalidBusinessSchema  When an identifier, owner identity, or checksum is malformed, the
     *          version is below one, the blueprint disagrees with the recorded
     *          definition or schema checksum, or the update precedes the install.
     *
     * @since   2.0.0
     */

### fromArray

/**
     * Rebuild an installation from its persisted row, re-proving the blueprint binding.
     *
     * @param   array<string, mixed>  $document  Stored installation object, as written by `toArray()`.
     *
     * @return  self  The revalidated installation, with both timestamps normalized to UTC.
     *
     * @throws  InvalidBusinessSchema  When the document carries an unknown property, a field is missing or
     *          misshapen, the stored status is not a known one, the blueprint is
     *          absent or invalid, or any installation invariant fails.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When a stored table's options
     *          cannot be canonically encoded.
     *
     * @since   2.0.0
     */

### disable

/**
     * Withdraw a live installation from record traffic while leaving its tables and rows untouched.
     *
     * This is the transition the extension lifecycle applies when an owner is deactivated.
     *
     * @param   DateTimeImmutable  $at  Instant to record as the update time.
     *
     * @return  self  A disabled copy of this installation.
     *
     * @throws  InvalidBusinessSchema  When the installation is not currently active, or $at precedes the
     *          install time.
     *
     * @since   2.0.0
     */

### reactivate

/**
     * Return a withheld installation to record traffic.
     *
     * The caller is responsible for having proved the tables still match this blueprint; this method only
     * enforces that the installation was withheld rather than mid-installation or failed.
     *
     * @param   DateTimeImmutable  $at  Instant to record as the update time.
     *
     * @return  self  An active copy of this installation.
     *
     * @throws  InvalidBusinessSchema  When the installation is neither disabled nor preserved, or $at
     *          precedes the install time.
     *
     * @since   2.0.0
     */

### preserve

/**
     * Hold an installation aside as intact but unusable, pending a deliberate reactivation.
     *
     * Unlike `disable()` this accepts an in-flight installation, which is what makes it the right
     * transition when an owner is deactivated mid-execution or a plan finalizes under an inactive owner.
     *
     * @param   DateTimeImmutable  $at  Instant to record as the update time.
     *
     * @return  self  A preserved copy of this installation.
     *
     * @throws  InvalidBusinessSchema  When the installation has failed, or $at precedes the install time.
     *
     * @since   2.0.0
     */

### toArray

/**
     * Export the installation in the shape persisted in the installation table.
     *
     * @return  array<string, mixed>  Identity, checksums, status, and the nested blueprint document, with
     *          both timestamps rendered as canonical UTC strings.
     *
     * @since   2.0.0
     */

## Kumwe\BusinessSchema\Domain\SchemaPlanStatus

/**
 * Position of a schema plan in the plan, approve, execute, recover lifecycle.
 *
 * The status is what separates the three independently authorized stages: a plan is compiled and stored
 * before anyone may approve it, approved against its exact checksum before anyone may execute it, and left
 * in an explicit interrupted state rather than a guessed one when a step cannot be reconciled. `SchemaPlan`
 * enforces which execution evidence each status may carry, so an impossible combination cannot be persisted.
 *
 * @since  2.0.0
 */

### terminal

/**
     * Report whether the plan has reached a state no further transition leaves.
     *
     * Callers such as the reactivation guard use this to distinguish an unfinished execution, which blocks
     * an installation from becoming usable again, from one that has been settled either way.
     *
     * @return  bool  True for completed, compensated, and cancelled plans.
     *
     * @since   2.0.0
     */

### cases

Generated enum/runtime member.

### from

Generated enum/runtime member.

### tryFrom

Generated enum/runtime member.

## Kumwe\BusinessSchema\Domain\PhysicalTableBlueprint

/**
 * Canonical description of one physical table, closed over its own columns, keys, indexes, and options.
 *
 * A table blueprint is the smallest unit a plan operation can name, and it is self-consistent by
 * construction: every primary-key, index, and foreign-key column is proven to exist in the same table, and
 * a set-null referential action is proven to land on nullable columns. Because the planner diffs blueprints
 * and the executor verifies live tables against them, collections are sorted and duplicate logical or
 * case-insensitive physical names are refused, so equal tables always serialize identically.
 *
 * @since  2.0.0
 */

### __construct

/**
     * Assemble a table and prove it is internally consistent.
     *
     * @param string $logicalName Handle a plan operation names this table by, such as `record`.
     * @param string $physicalName Installed table name, with the configured prefix already applied.
     * @param PhysicalTableKind $kind Whether the table holds records, links, or owned lines.
     * @param   list<PhysicalColumnBlueprint>      $columns       Columns in any order; at least one, at most 512.
     * @param array<array-key, string> $primaryKey Physical column names in key order; at most 16, all present
     * in $columns.
     * @param   list<PhysicalIndexBlueprint>       $indexes       Indexes whose columns must all belong to this table.
     * @param   list<PhysicalForeignKeyBlueprint>  $foreignKeys   Constraints whose local columns must belong here.
     * @param array<string, mixed> $options Portable table metadata; sorted by key before it is stored.
     *
     * @throws  InvalidBusinessSchema  When a name breaks its grammar, the column collection is empty or over
     *          the bound, two columns, indexes, or foreign keys collide, the primary
     *          key is empty, oversized, repeated, or references a column outside the
     *          table, an index or foreign key references a column outside the table,
     *          a set-null action lands on a non-nullable column, or the options are
     *          not a string-keyed object.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the options hold a value
     *          that cannot be canonically encoded, such as a float or an object.
     *
     * @since   2.0.0
     */

### fromArray

/**
     * Rebuild a table from its persisted document, revalidating every invariant.
     *
     * @param   array<string, mixed>  $document  Stored table object, as written by `toArray()`.
     *
     * @return  self  The revalidated table, with its collections back in canonical order.
     *
     * @throws  InvalidBusinessSchema  When the document carries an unknown property, a field is missing or
     *          misshapen, the stored kind is not a known one, or any table invariant
     *          fails.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the stored options hold
     *          a value that cannot be canonically encoded.
     *
     * @since   2.0.0
     */

### columns

/**
     * List every column of the table.
     *
     * @return  list<PhysicalColumnBlueprint>  The columns in canonical order, never empty.
     *
     * @since   2.0.0
     */

### column

/**
     * Resolve the column a definition field handle maps to.
     *
     * @param   string  $logicalName  Logical column handle, as a plan operation's subject names it.
     *
     * @return  PhysicalColumnBlueprint|null  The matching column, or null when this table declares none.
     *
     * @since   2.0.0
     */

### physicalColumn

/**
     * Resolve a column from the installed name a key or constraint refers to.
     *
     * @param   string  $physicalName  Installed column name.
     *
     * @return  PhysicalColumnBlueprint|null  The matching column, or null when this table declares none.
     *
     * @since   2.0.0
     */

### indexes

/**
     * List the indexes and unique constraints this table declares.
     *
     * @return  list<PhysicalIndexBlueprint>  The indexes in canonical order; empty when the table has none
     *          beyond its primary key.
     *
     * @since   2.0.0
     */

### foreignKeys

/**
     * List the referential constraints leaving this table.
     *
     * @return  list<PhysicalForeignKeyBlueprint>  The constraints in canonical order; empty when the table
     *          references nothing.
     *
     * @since   2.0.0
     */

### toArray

/**
     * Export the table in the shape that is persisted inside a schema blueprint.
     *
     * @return  array<string, mixed>  Names, kind, and the four collections, each already in canonical order.
     *
     * @since   2.0.0
     */

## Kumwe\BusinessSchema\Domain\PhysicalTableKind

/**
 * Role a generated table plays inside a compiled business schema.
 *
 * Every `PhysicalTableBlueprint` carries one of these, and the value travels with the persisted blueprint
 * document, so a later plan, live introspection, or backup-acceptance check can tell a definition's own
 * record table apart from the tables generated to carry its relationships and ordered lines. It classifies
 * where the table came from, not how it is stored.
 *
 * @since  2.0.0
 */

### cases

Generated enum/runtime member.

### from

Generated enum/runtime member.

### tryFrom

Generated enum/runtime member.

## Kumwe\BusinessSchema\Domain\SchemaRecoveryEvidence

/**
 * Signed-off proof that a restore drill succeeded for one site, engine, release, and source schema.
 *
 * A rebuild-or-locking or destructive plan may be neither approved nor executed unless a record like
 * this exists, is fresh, and matches the environment the plan will run in. Every field is therefore a
 * binding rather than a note: the source schema checksum ties the drill to the exact schema about to
 * change, the driver, server version, and release tie it to the binary that will do the changing, and
 * the two timestamps let the approval path reject a drill that predates the plan or the freshness
 * floor. Storage treats the record as immutable and compares its checksum before refusing to replace
 * it, so a drill cannot be quietly re-scoped after the fact.
 *
 * @since  2.0.0
 */

### __construct

/**
     * Record one verified restore drill, refusing anything internally inconsistent.
     *
     * @param   string                $id                      Canonical UUID naming this drill record.
     * @param   string                $siteIdentifier          Site the drill was performed for.
     * @param   string                $databaseDriver          Engine drilled: mariadb, mysql, or pgsql.
     * @param   string                $databaseServerVersion   Server version the drill ran against.
     * @param   string                $applicationRelease      Kumwe release that performed the drill.
     * @param   string                $sourceSchemaChecksum    Checksum of the schema the backup covers.
     * @param   string                $backupManifestChecksum  Digest of the manifest that was restored.
     * @param   bool                  $restoreTested           Whether the backup was restored, not just taken.
     * @param   DateTimeImmutable     $backupCreatedAt         When the backup being vouched for was made.
     * @param   DateTimeImmutable     $verifiedAt              When the restore was verified.
     * @param   string                $verifiedBy              Actor who signed the drill result off.
     * @param   string                $drillReference          Operator-facing reference for the drill run.
     * @param   array<string, mixed>  $details                 Further proofs; sorted before it is stored.
     *
     * @throws  InvalidBusinessSchema  When the ID is not a UUID, the site is not a metadata identifier,
     *          the driver is outside the three supported engines, the version, release, verifier, or
     *          drill reference is empty, over 191 bytes, or holds control characters, either checksum is
     *          not a lowercase SHA-256 digest, the verification predates the backup, or the details are
     *          not a string-keyed object.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the details hold a
     *          value that cannot be canonically encoded, such as a float or an object.
     *
     * @since   2.0.0
     */

### fromArray

/**
     * Rebuild a drill record from the row the evidence repository read.
     *
     * @param   array<string, mixed>  $document  Stored evidence object, as written by `toArray()`.
     *
     * @return  self  The revalidated record, subject to every construction rule again.
     *
     * @throws  InvalidBusinessSchema  When the document carries an unknown property, a field is absent
     *          or misshapen, a timestamp is unreadable, or a construction rule fails.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the stored details
     *          hold a value that cannot be canonically encoded.
     *
     * @since   2.0.0
     */

### qualifies

/**
     * Decide whether this drill may back a plan about to run in the given environment.
     *
     * Every binding has to match and both timestamps have to clear the floor, so a drill from another
     * site, an upgraded engine, a different release, or an older source schema never qualifies. The
     * caller chooses the floor, which is how the same record can be fresh enough to approve a plan and
     * too old to execute it later.
     *
     * @param   string             $siteIdentifier      Site the plan will execute against.
     * @param   string             $driver              Engine the executor is bound to.
     * @param   string             $serverVersion       Server version the executor is configured for.
     * @param   string             $applicationRelease  Release that will perform the execution.
     * @param   string             $schemaChecksum      Source schema checksum the plan starts from.
     * @param   DateTimeImmutable  $notBefore           Floor both drill timestamps must be at or after.
     *
     * @return  bool  True only when the backup was actually restored and every binding matches.
     *
     * @since   2.0.0
     */

### toArray

/**
     * Export the drill in the document shape it is persisted, compared, and hashed as.
     *
     * @return  array<string, mixed>  Every binding plus the sorted details, with both timestamps
     *          rendered as the fixed-width UTC text schema documents persist.
     *
     * @since   2.0.0
     */

### checksum

/**
     * Compute the content address that makes this record immutable in storage.
     *
     * The repository recomputes it before it will accept a write against an identifier it already
     * holds, so a second save is either byte-identical or refused.
     *
     * @return  string  Lowercase SHA-256 over the canonical JSON encoding of `toArray()`.
     *
     * @since   2.0.0
     */

## Kumwe\BusinessSchema\Domain\SchemaInstallationStatus

/**
 * Availability state of one definition's installed physical schema on a site.
 *
 * This is the gate the record runtime consults before it will touch generated tables: only `Active`
 * admits ordinary record commands, while the remaining states keep physical data and history intact but
 * fail record traffic closed. The executor and the extension lifecycle move an installation between these
 * states; the state never says whether the tables exist, only whether they may be used.
 *
 * @since  2.0.0
 */

### cases

Generated enum/runtime member.

### from

Generated enum/runtime member.

### tryFrom

Generated enum/runtime member.

## Kumwe\BusinessSchema\Domain\PhysicalForeignKeyBlueprint

/**
 * Canonical description of one referential constraint leaving a physical table.
 *
 * A foreign key is what makes a compiled definition graph hold together, so it is validated on its own
 * terms before the owning table ever sees it: the two column lists have to pair up one for one, stay
 * within the bound an engine will accept, and repeat no column. Referential actions are limited to the
 * four every supported engine spells the same way, which is what lets the planner emit one blueprint and
 * the executor verify a live constraint against it without engine-specific translation. Only the owning
 * `PhysicalTableBlueprint` can prove the local columns exist and that a set-null action lands on nullable
 * ones; this type deliberately stops at the properties a constraint can check about itself.
 *
 * @since  2.0.0
 */

### __construct

/**
     * Assemble a constraint and prove its column pairing and referential actions are usable.
     *
     * @param   string        $logicalName     Handle a plan operation names this constraint by.
     * @param   string        $physicalName    Installed constraint name, with the configured prefix already applied.
     * @param   list<string>  $localColumns    Physical columns in constraint order.
     * @param   string        $foreignTable    Installed name of the referenced table, with the prefix already applied.
     * @param   list<string>  $foreignColumns  Physical target columns in constraint order.
     * @param   string        $onDelete        Action taken when a referenced row is deleted.
     * @param   string        $onUpdate        Action taken when a referenced key is updated.
     *
     * @throws  InvalidBusinessSchema  When a name or the target table breaks its grammar, either column list is
     *          empty, longer than 16, or repeats a column, the two lists differ in length, a column name is not
     *          a portable identifier, or either referential action is outside the supported four.
     *
     * @since   2.0.0
     */

### fromArray

/**
     * Rebuild a constraint from its persisted document, revalidating every rule the constructor applies.
     *
     * @param   array<string, mixed>  $document  Stored foreign-key object, as written by `toArray()`.
     *
     * @return  self  The revalidated constraint.
     *
     * @throws  InvalidBusinessSchema  When the document carries an unknown property, a field is missing or
     *          misshapen, or any constraint rule fails.
     *
     * @since   2.0.0
     */

### toArray

/**
     * Export the constraint in the shape that is persisted inside a table blueprint.
     *
     * @return  array<string, mixed>  Keyed `logical_name`, `physical_name`, `local_columns`, `foreign_table`,
     *          `foreign_columns`, `on_delete`, and `on_update`, with both column lists in constraint order.
     *
     * @since   2.0.0
     */

## Kumwe\BusinessSchema\Domain\PhysicalColumnBlueprint

/**
 * Canonical description of one column of a physical table, restricted to the portable Doctrine subset.
 *
 * The planner diffs these blueprints and the executor verifies live columns against them, so a column has
 * to mean the same thing on every supported engine: the Doctrine type comes from a fixed list, only the
 * portable options are accepted, and nullability is a property of its own rather than a `notnull` option
 * an engine could fold differently. A default is proven expressible in the exact type it belongs to, and
 * the options map is proven canonically encodable and key sorted, so two equal columns always serialize —
 * and therefore checksum — identically.
 *
 * @since  2.0.0
 */

### __construct

/**
     * Assemble a column and prove its type, options, and default are portable and mutually consistent.
     *
     * @param   string                $logicalName   Handle a plan operation and the compiler name this column by.
     * @param   string                $physicalName  Installed column name, already compiled to the portable grammar.
     * @param   string                $doctrineType  One of the accepted Doctrine type names.
     * @param   array<string, mixed>  $options       Portable Doctrine options; key sorted before they are stored.
     * @param   bool                  $nullable      Whether the installed column accepts NULL.
     *
     * @throws  InvalidBusinessSchema  When either name breaks its grammar, the Doctrine type is outside the
     *          portable set, an option is unknown or expresses nullability, a decimal lacks a valid precision
     *          and scale, a length or fixed option is malformed or sits on a type that carries no length, an
     *          autoincrement column is nullable or not an integer, the comment is not a string of at most 255
     *          bytes, or the default does not match the column's exact type.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the options hold a value
     *          canonical JSON cannot reproduce, such as a float or an object.
     *
     * @since   2.0.0
     */

### fromArray

/**
     * Rebuild a column from its persisted document, revalidating every rule the constructor applies.
     *
     * @param   array<string, mixed>  $document  Stored column object, as written by `toArray()`.
     *
     * @return  self  The revalidated column, with its options back in canonical order.
     *
     * @throws  InvalidBusinessSchema  When the document carries an unknown property, a field is missing or
     *          misshapen, or any column rule fails.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the stored options hold a
     *          value canonical JSON cannot reproduce.
     *
     * @since   2.0.0
     */

### toArray

/**
     * Export the column in the shape that is persisted inside a table blueprint.
     *
     * @return  array<string, mixed>  Keyed `logical_name`, `physical_name`, `doctrine_type`, `options`, and
     *          `nullable`, with the options already in canonical order.
     *
     * @since   2.0.0
     */

## Kumwe\BusinessSchema\Domain\SchemaRisk

/**
 * Impact class of a schema operation, and the approval ceremony the plan containing it inherits.
 *
 * Every operation declares one of these, and a plan's risk must equal the highest its operations carry,
 * so one destructive step raises the whole plan. The class is what the approval path reads: it decides
 * whether an approver has to supply a high-impact confirmation, whether tested recovery evidence bound
 * to the source schema must exist and still be fresh, and — for the destructive class — whether a
 * separate authorization is demanded again at execution time.
 *
 * @since  2.0.0
 */

### severity

/**
     * Rank this class on the single scale plan risk is compared by.
     *
     * The scale is not declaration order: a rebuild or locking change outranks a behaviour change,
     * because the stall it imposes is the harder thing for an operator to schedule around.
     *
     * @return  int  Zero for an online-safe addition, rising to four for a destructive change.
     *
     * @since   2.0.0
     */

### requiresHighImpactAuthorization

/**
     * Report whether approving a plan at this class demands the high-impact confirmation.
     *
     * @return  bool  True for every class except an online-safe addition.
     *
     * @since   2.0.0
     */

### requiresRecoveryEvidence

/**
     * Report whether a plan at this class may only proceed against a tested restore drill.
     *
     * @return  bool  True for rebuild-or-locking and destructive changes, which are the two classes a
     *          failed execution cannot be talked out of without a backup.
     *
     * @since   2.0.0
     */

### highest

/**
     * Reduce the risks of a plan's operations to the one class that classifies the plan.
     *
     * @param   iterable<self>  $risks  Risk declared by each operation, in any order.
     *
     * @return  self  The most severe class present; an online-safe addition when nothing was supplied.
     *
     * @since   2.0.0
     */

### cases

Generated enum/runtime member.

### from

Generated enum/runtime member.

### tryFrom

Generated enum/runtime member.

## Kumwe\BusinessSchema\Domain\InvalidBusinessSchema

/**
 * Signals that a business-schema document breaks a rule this namespace enforces.
 *
 * Blueprints, plans, steps, and recovery evidence all validate themselves as they are constructed and
 * are all rebuilt from untrusted arrays through `SchemaDocument`, so the whole namespace raises this
 * one type: an importer, a repository hydrating a row, or a delivery adapter has a single class to
 * catch whichever field, identifier, checksum, or portability rule was broken. It extends
 * `InvalidArgumentException` because a rejected document is bad input rather than a broken
 * installation, which is what lets `BusinessApiResponder` answer it with 422 instead of a fault.
 * Messages name the rule that failed and stay operator-facing.
 *
 * @since  2.0.0
 */

## Kumwe\BusinessSchema\Domain\SchemaOperation

/**
 * One approvable, journaled step of a schema plan, described semantically rather than as SQL.
 *
 * An operation says what should change, what the affected object looked like before, and what it must look
 * like afterwards; the physical gateway turns that into statements for the driver in use and uses the same
 * two states to decide whether the step is already satisfied. It also carries the two facts recovery needs
 * before anything runs: how risky the step is, and what an operator must do if execution stops on it.
 * Operations are content addressed, so a persisted step cannot be edited without invalidating its plan.
 *
 * @since  2.0.0
 */

### __construct

/**
     * Describe one step and prove it is coherent before it can be planned.
     *
     * @param   int                        $ordinal              Position in the plan, from one, gapless.
     * @param   SchemaOperationKind        $kind                 Semantic change the gateway must realise.
     * @param   SchemaRisk                 $risk                 Impact class this step contributes to the plan.
     * @param   string                     $table                Logical table the step acts on.
     * @param   string                     $subject              Logical object within that table, or a path.
     * @param array<string, mixed>|null $before Prior state of the subject, for verification and recovery.
     * @param array<string, mixed>|null $after Target state of the subject, for execution and verification.
     * @param   bool                       $requiresBackfill     Whether the step rewrites rows rather than shape.
     * @param   string                     $recoveryImplication  One of the declared recovery implications.
     *
     * @throws  InvalidBusinessSchema  When the ordinal is outside one to 100000, the table is not a
     *          metadata identifier, the subject is over 512 bytes or is neither a
     *          metadata identifier nor a slash path, the recovery implication is
     *          not a declared one, a row-rewriting step claims to be online-safe
     *          additive, or either state is not a string-keyed object.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When either state holds a
     *          value that cannot be canonically encoded, such as a float or an object.
     *
     * @since   2.0.0
     */

### fromArray

/**
     * Rebuild an operation from its persisted document and confirm it was not tampered with.
     *
     * When the stored document carries a `checksum`, it is compared against the checksum recomputed from
     * the decoded content, so an edited journal row is refused rather than replayed.
     *
     * @param   array<string, mixed>  $document  Stored operation object, as written by `persistedArray()`.
     *
     * @return  self  The revalidated operation.
     *
     * @throws  InvalidBusinessSchema  When the document carries an unknown property, a field is missing or
     *          misshapen, the stored kind or risk is not a known one, an operation
     *          invariant fails, or the stored checksum does not match the content.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When a stored state holds a
     *          value that cannot be canonically encoded.
     *
     * @since   2.0.0
     */

### toArray

/**
     * Export the content that defines this operation's identity.
     *
     * The checksum is deliberately excluded, which is what lets it be computed over this array.
     *
     * @return  array<string, mixed>  Ordinal, kind, risk, target, both states, and the recovery facts.
     *
     * @since   2.0.0
     */

### persistedArray

/**
     * Export the operation in the shape written to the step journal.
     *
     * @return  array<string, mixed>  The canonical content plus a `checksum` entry `fromArray()` verifies.
     *
     * @since   2.0.0
     */

### checksum

/**
     * Compute the content address of this operation.
     *
     * @return  string  Lowercase SHA-256 over the canonical JSON encoding of `toArray()`.
     *
     * @since   2.0.0
     */

## Kumwe\BusinessSchema\Domain\PhysicalSchemaBlueprint

/**
 * Canonical description of every physical table one published definition version installs.
 *
 * This is the unit that gets checksummed and compared: the compiler produces one from a definition, the
 * planner diffs the installed blueprint against the target to derive operations, the executor verifies the
 * result against live introspection, and the installation record stores the blueprint it settled on. Table
 * order is normalized on construction and logical and physical names are proven unique, so two blueprints
 * describing the same schema always produce the same checksum regardless of how they were assembled.
 *
 * @since  2.0.0
 */

### __construct

/**
     * Compile a schema from the tables one definition version resolves to.
     *
     * @param string $definitionId UUID of the business definition this schema belongs to.
     * @param   int                           $definitionVersion   Published version the tables were compiled from.
     * @param string $definitionChecksum SHA-256 of that published definition, so drift is detectable.
     * @param list<PhysicalTableBlueprint> $tables Tables to install, in any order; at least one, at most 512.
     *
     * @throws  InvalidBusinessSchema  When the identifier or checksum is malformed, the version is below
     *          one, the table collection is empty or over the bound, or two tables
     *          share a logical name or a case-insensitive physical name.
     *
     * @since   2.0.0
     */

### fromArray

/**
     * Rebuild a blueprint from its persisted document, revalidating every table.
     *
     * @param   array<string, mixed>  $document  Stored blueprint object, as written by `toArray()`.
     *
     * @return  self  The revalidated blueprint, with its tables back in canonical order.
     *
     * @throws  InvalidBusinessSchema  When the document carries an unknown property, a field is missing or
     *          misshapen, or any table breaks a schema or table invariant.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When a table's stored options
     *          cannot be canonically encoded.
     *
     * @since   2.0.0
     */

### tables

/**
     * List every table this schema installs.
     *
     * @return  list<PhysicalTableBlueprint>  The tables in canonical order, never empty.
     *
     * @since   2.0.0
     */

### table

/**
     * Look a table up by the logical handle a plan operation names it with.
     *
     * @param   string  $logicalHandle  Logical table name, such as `record` or `relation:<handle>`.
     *
     * @return  PhysicalTableBlueprint|null  The matching table, or null when this schema declares none.
     *
     * @since   2.0.0
     */

### toArray

/**
     * Export the blueprint in the shape that is persisted and checksummed.
     *
     * @return  array<string, mixed>  The definition binding plus a `tables` list in canonical order.
     *
     * @since   2.0.0
     */

### checksum

/**
     * Compute the identity of this schema for approval, installation, and drift checks.
     *
     * @return  string  Lowercase SHA-256 over the canonical JSON encoding of `toArray()`.
     *
     * @since   2.0.0
     */

## Kumwe\BusinessSchema\Domain\SchemaEvolutionHints

/**
 * The declared, bounded instructions that let a plan rewrite data instead of refusing to.
 *
 * A rename, a type change, or a tightened constraint cannot be applied over rows pinned to an older
 * definition version unless the new version says how those rows should be carried across. This value
 * object is the only reading of that intent: it lifts the four evolution families out of a definition's
 * compatibility metadata, normalizes them, and proves them bounded and unambiguous — no rename cycles, no
 * two renames landing on one column, no unbounded expression — so the planner can consult them freely.
 *
 * A typo is treated as a defect rather than as absence: a metadata key that reads like an evolution key
 * but is not one of the four is rejected, so a misspelled hint cannot silently degrade into "no hint".
 *
 * @since  2.0.0
 */

### fromDefinition

/**
     * Read the evolution hints a published definition declares in its compatibility metadata.
     *
     * Metadata unrelated to schema evolution is ignored, but a key that merely looks like one of the four
     * families is refused, because tolerating it would silently skip the rewrite it was meant to authorize.
     *
     * @param   EntityTypeDefinition  $definition  Definition version whose metadata carries the intent.
     *
     * @return  self  The parsed hints; every family is empty when the definition declares none.
     *
     * @throws  InvalidBusinessSchema  When a metadata key is not a string, an evolution-looking key is not
     *          one of the four families, or a declared family is malformed.
     *
     * @since   2.0.0
     */

### fromArray

/**
     * Parse hints from a document holding only the four evolution families.
     *
     * @param   array<string, mixed>  $document  Evolution families as declared; each one may be absent.
     *
     * @return  self  The parsed hints, with every family key sorted for a stable checksum.
     *
     * @throws  InvalidBusinessSchema  When the document holds a key outside the four families, a family is
     *          not an object, a family exceeds 256 entries, a name breaks its
     *          grammar, a rename is ambiguous, chained, or cyclic, a backfill is not
     *          a bounded scalar or expression, a transform is not a bounded
     *          expression, or a repin version is not positive.
     *
     * @since   2.0.0
     */

### renameForTable

/**
     * Look up the column renames declared for one logical table.
     *
     * @param   string  $logicalTable  Logical table name; unqualified renames belong to `record`.
     *
     * @return  array<string, string>  Old logical column to new logical column; empty when the table has
     *          no declared renames.
     *
     * @throws  InvalidBusinessSchema  When the table name is not a metadata identifier.
     *
     * @since   2.0.0
     */

### hasBackfill

/**
     * Report whether a backfill is declared for a logical column.
     *
     * @param   string  $logicalColumn  Logical column handle to test.
     *
     * @return  bool  True when the definition declares a value to write into pre-existing rows.
     *
     * @throws  InvalidBusinessSchema  When the column name is not a metadata identifier.
     *
     * @since   2.0.0
     */

### backfill

/**
     * Read the value a backfill writes into rows that predate a column.
     *
     * @param   string  $logicalColumn  Logical column handle to read.
     *
     * @return  bool|int|string|Expression|null  The declared literal or expression; null means no backfill
     *          is declared, since a declared one is never null.
     *
     * @throws  InvalidBusinessSchema  When the column name is not a metadata identifier.
     *
     * @since   2.0.0
     */

### transform

/**
     * Read the expression that converts a logical column's existing values.
     *
     * @param   string  $logicalColumn  Logical column handle to read.
     *
     * @return  Expression|null  The declared conversion, or null when the column keeps its values as they
     *          are.
     *
     * @throws  InvalidBusinessSchema  When the column name is not a metadata identifier.
     *
     * @since   2.0.0
     */

### transforms

/**
     * List every declared column conversion, which is how the planner finds rewritten columns.
     *
     * @return  array<string, Expression>  Conversions keyed by logical column, sorted by key.
     *
     * @since   2.0.0
     */

### renames

/**
     * List every declared rename, grouped by the table it applies to.
     *
     * @return  array<string, array<string, string>>  Old to new logical column names, keyed by table.
     *
     * @since   2.0.0
     */

### backfills

/**
     * List every declared backfill.
     *
     * @return  array<string, bool|int|string|Expression>  Values keyed by logical column, sorted by key.
     *
     * @since   2.0.0
     */

### repin

/**
     * Read the definition version a handle's stored records must be re-pinned to.
     *
     * A declared repin is what authorizes a plan to rewrite historical rows at all, so a null here means
     * older rows stay on their pinned version and the destructive follow-up work stays blocked.
     *
     * @param   string  $definitionHandle  Namespaced definition handle, such as `vendor.thing`.
     *
     * @return  int|null  The target version, or null when this handle is not re-pinned.
     *
     * @throws  InvalidBusinessSchema  When the handle is over 191 bytes or is not namespaced.
     *
     * @since   2.0.0
     */

### repins

/**
     * List every declared repin.
     *
     * @return  array<string, int>  Target versions keyed by definition handle, sorted by key.
     *
     * @since   2.0.0
     */

### toArray

/**
     * Export the hints in their canonical document shape.
     *
     * Renames are flattened back to `table/old` keys, so the round trip through `fromArray()` yields the
     * same object regardless of whether the source spelled a rename with or without its table.
     *
     * @return  array<string, mixed>  All four families, always present, each sorted by key; expressions are
     *          rendered as their own array form.
     *
     * @since   2.0.0
     */

### checksum

/**
     * Compute a stable identity for the declared intent, so a plan can be bound to it.
     *
     * @return  string  Lowercase SHA-256 over the canonical JSON encoding of `toArray()`.
     *
     * @since   2.0.0
     */

## Kumwe\BusinessSchema\Domain\SchemaOperationKind

/**
 * Portable vocabulary of semantic changes a schema plan may contain.
 *
 * A plan never persists executable SQL: it persists one of these kinds plus the before/after blueprint
 * state, and the physical gateway decides what statements the current driver needs. That indirection is
 * what lets an administrator approve an exact plan, lets the executor journal and replay each step, and
 * lets live introspection confirm afterwards that the intended change actually landed.
 *
 * @since  2.0.0
 */

### cases

Generated enum/runtime member.

### from

Generated enum/runtime member.

### tryFrom

Generated enum/runtime member.

## Kumwe\BusinessSchema\Domain\PhysicalNameCompiler

/**
 * Turns the logical metadata of a business definition into the physical identifiers its tables install under.
 *
 * Definition handles are long and human-chosen, while a physical identifier has to fit a 63-byte portable
 * budget and stay distinct from every other definition sharing the database. This compiler resolves that by
 * pairing a truncated readable stem with a digest over the full metadata, so a name is short enough to
 * install, recognisable enough to read in a console, and still distinct once the readable part has been cut.
 * Compilation is pure and deterministic, which is what lets `BusinessSchemaExecutor` recompile a definition's
 * blueprint at execution time and demand that it hash identically to the one the plan was approved against.
 * One instance is wired per runtime with the site's configured table prefix, and
 * `CanonicalDefinitionPhysicalSchemaCompiler` is its only caller outside that wiring.
 *
 * @since  2.0.0
 */

### __construct

/**
     * Bind the compiler to the table prefix its names will be installed under.
     *
     * The prefix is checked here rather than trusted, because it is the one part of a compiled identifier
     * that comes from configuration instead of from validated definition metadata.
     *
     * @param   string  $prefix  Configured table prefix, bounded to 28 bytes and canonical
     * underscore-terminated grammar.
     *
     * @throws  InvalidBusinessSchema  When the prefix is not a canonical, underscore-terminated prefix.
     *
     * @since   2.0.0
     */

### entityTable

/**
     * Compile the table that stores the records of one entity type.
     *
     * @param   string  $definitionId  UUID of the business definition owning the table.
     * @param   string  $handle        Site-qualified definition handle, such as `site.default.invoice`.
     *
     * @return  string  Prefixed name in the `e` category, fixed by the definition ID and handle together.
     *
     * @throws  InvalidBusinessSchema  When the definition ID is not a UUID, the handle is not a metadata
     *          identifier, or the compiled name is not a portable identifier.
     *
     * @since   2.0.0
     */

### relationTable

/**
     * Compile the join table that links two entity types across one relationship.
     *
     * @param   string  $definitionId  UUID of the definition declaring the relationship.
     * @param   string  $handle        Relationship handle as the definition names it.
     *
     * @return  string  Prefixed name in the `r` category, distinct from the `e` name of the same metadata.
     *
     * @throws  InvalidBusinessSchema  When the definition ID is not a UUID, the handle is not a metadata
     *          identifier, or the compiled name is not a portable identifier.
     *
     * @since   2.0.0
     */

### lineTable

/**
     * Compile the table that holds the owned line items of one entity type.
     *
     * @param   string  $definitionId  UUID of the definition that owns the lines.
     * @param   string  $handle        Line-collection handle as the owning definition names it.
     *
     * @return  string  Prefixed name in the `l` category, distinct from the `e` name of the same metadata.
     *
     * @throws  InvalidBusinessSchema  When the definition ID is not a UUID, the handle is not a metadata
     *          identifier, or the compiled name is not a portable identifier.
     *
     * @since   2.0.0
     */

### column

/**
     * Compile the column a logical field handle is stored in.
     *
     * Unlike the table methods this takes no definition ID, so the same handle yields the same column name
     * in every table. That is deliberate: a column only has to be unique inside its own table, and a shared
     * name keeps a field recognisable wherever the compiler reuses it.
     *
     * @param   string  $logicalName  Field or control-column handle, such as `field.title` or `record_id`.
     *
     * @return  string  Prefixed name in the `c` category.
     *
     * @throws  InvalidBusinessSchema  When the handle is not a metadata identifier, or the compiled name is
     *          not a portable identifier.
     *
     * @since   2.0.0
     */

### index

/**
     * Compile the name of an index on a table.
     *
     * The covered columns take part in the digest, so changing which columns an index spans produces a
     * different name. That is what lets the planner treat a respanned index as a drop and a create instead
     * of silently leaving the installed one in place under a name that no longer describes it.
     *
     * @param   string        $tableLogicalName  Handle of the owning table; the schema compiler passes the
     *          compiled table name so the index inherits its definition scope.
     * @param   string        $logicalName       Handle the index is known by within that table.
     * @param   list<string>  $columns           Physical columns the index covers, in index order.
     *
     * @return  string  Prefixed name in the `i` category.
     *
     * @throws  InvalidBusinessSchema  When either handle is not a metadata identifier, a column is neither a
     *          UUID nor a metadata identifier, the two handles and the columns together
     *          exceed 64 parts, or the compiled name is not a portable identifier.
     *
     * @since   2.0.0
     */

### foreignKey

/**
     * Compile the name of a foreign-key constraint on a table.
     *
     * @param   string        $tableLogicalName  Handle of the table the constraint leaves; the schema compiler
     *          passes the compiled table name.
     * @param   string        $logicalName       Handle the constraint is known by within that table.
     * @param   list<string>  $columns           Local physical columns carrying the reference, in constraint order.
     *
     * @return  string  Prefixed name in the `f` category, distinct from the `i` name of the same columns.
     *
     * @throws  InvalidBusinessSchema  When either handle is not a metadata identifier, a column is neither a
     *          UUID nor a metadata identifier, the two handles and the columns together
     *          exceed 64 parts, or the compiled name is not a portable identifier.
     *
     * @since   2.0.0
     */

### compile

/**
     * Compile any prefixed physical name from a category tag and the metadata that identifies the object.
     *
     * This is the single implementation the six named helpers delegate to, and it is public so a caller
     * naming an object those helpers do not cover can join the same namespace. The digest is taken over the
     * category and the lowercased parts joined by NUL bytes — a byte the accepted grammars cannot contain —
     * so no two part collections share a digest input, and the category keeps a table and an index built
     * from the same metadata apart. Only the readable stem is shortened to reach the budget; the digest is
     * appended whole, so truncation can never make two objects collide.
     *
     * @param   string        $category  Short lowercase tag separating one family of names from another,
     *          such as `e` for entity tables or `i` for indexes.
     * @param   list<string>  $parts     Metadata identifying the object, in the order it should read.
     *
     * @return  string  Prefix, category, a truncated readable stem, and the digest, at most 63 bytes.
     *
     * @throws  InvalidBusinessSchema  When the category is outside its 16-byte lowercase grammar, no parts
     *          are supplied, more than 64 are, a part is neither a UUID nor a metadata
     *          identifier, or the compiled name is not a portable identifier.
     *
     * @since   2.0.0
     */

## Kumwe\BusinessSchema\Domain\PhysicalIndexBlueprint

/**
 * Canonical description of one index or unique constraint on a physical table.
 *
 * The schema compiler emits one of these for every field, relationship, or foreign key that needs a lookup
 * path, and the Doctrine gateway both installs and verifies live indexes against it. That round trip only
 * works if the description is engine neutral, so an index is bounded to sixteen distinct physical columns
 * and carries no engine-specific options at all: a non-empty option map is refused rather than stored and
 * quietly dropped on an engine that cannot honour it. Whether those columns actually exist is not this
 * type's business — the owning `PhysicalTableBlueprint` proves that once it holds the column collection.
 *
 * @since  2.0.0
 */

### __construct

/**
     * Assemble an index and prove its column list is bounded, distinct, and portable.
     *
     * @param   string                $logicalName   Handle a plan operation names this index by.
     * @param   string                $physicalName  Installed index name, as the physical name compiler produced it.
     * @param   array<array-key, string>          $columns       Physical column names in index order.
     * @param   bool                  $unique        Whether the index also forbids duplicate values over $columns.
     * @param   array<string, mixed>  $options       Portable Doctrine index options.
     *
     * @throws  InvalidBusinessSchema  When either name breaks its grammar, the column list is empty, longer
     *          than 16, or repeats a column, a column is not a portable physical
     *          identifier, or the options are a list, use a non-string key, or are
     *          non-empty at all.
     *
     * @since   2.0.0
     */

### fromArray

/**
     * Rebuild an index from its persisted document, revalidating every rule the constructor applies.
     *
     * @param   array<string, mixed>  $document  Stored index object, as written by `toArray()`.
     *
     * @return  self  The revalidated index.
     *
     * @throws  InvalidBusinessSchema  When the document carries an unknown property, a field is missing or
     *          misshapen, or any index rule fails.
     *
     * @since   2.0.0
     */

### toArray

/**
     * Export the index in the shape that is persisted inside a table blueprint.
     *
     * @return  array<string, mixed>  Keyed `logical_name`, `physical_name`, `columns`, `unique`, and
     *          `options`, with the columns in index order.
     *
     * @since   2.0.0
     */

## Kumwe\BusinessSchema\Domain\SchemaPlanStep

/**
 * Durable journal entry for one operation of a schema plan, rewritten before and after every attempt.
 *
 * The executor never asks the live database how far a plan got; it reads these rows. Each one records
 * which attempt is current, the execution fence that attempt holds, the schema checksum the step began
 * from, the keyset a long rewrite reached, and the outcome or error code it ended with. A resumed
 * execution can therefore skip finished steps, continue a half-written rewrite from its cursor, and —
 * because every row names the fence that wrote it — let the repository refuse a write from a
 * superseded run. Every transition returns a new instance, and each instance re-checks that its state
 * and the evidence it carries actually agree.
 *
 * @since  2.0.0
 */

### __construct

/**
     * Capture one journal entry and prove its state and its evidence agree.
     *
     * @param   string                               $planId                Plan this step belongs to, as a UUID.
     * @param   int                                  $ordinal               Position in the plan, from one, gapless.
     * @param   string                               $operationChecksum     Content address of the operation.
     * @param   SchemaOperationKind                  $operationKind         Semantic change this step realises.
     * @param   SchemaRisk                           $risk                  Impact class the operation carries.
     * @param   SchemaStepStatus                     $state                 Journal state this instance represents.
     * @param   int                                  $attempt               Attempts started; zero while pending.
     * @param   ?int                                 $executionFence        Fence of the current attempt.
     * @param   array<string, mixed>|null  $cursor                Keyset a chunked rewrite reached.
     * @param   ?string                              $beforeSchemaChecksum  Schema checksum the attempt began from.
     * @param   ?string                              $afterSchemaChecksum   Schema checksum the step produced.
     * @param   array<string, mixed>|null            $outcome               Facts the finished attempt recorded.
     * @param   ?string                              $errorCode             Bounded code naming why it failed.
     * @param   ?DateTimeImmutable                   $startedAt             When the current attempt started.
     * @param   ?DateTimeImmutable                   $completedAt           When the step became terminal.
     * @param   ?DateTimeImmutable                   $updatedAt             When this row was last written.
     *
     * @throws  InvalidBusinessSchema  When the plan ID is not a UUID, the ordinal or attempt is outside
     *          its bounds, a checksum is not a lowercase SHA-256 digest, the fence is not positive,
     *          the error code breaks its grammar, the cursor or outcome is not a string-keyed object,
     *          the cursor holds a value that is not a bool, int, or string, the state and the recorded
     *          evidence disagree, or a completion or update time precedes the start.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the cursor or the
     *          outcome holds a value that cannot be canonically encoded, such as a float or an object.
     *
     * @since   2.0.0
     */

### fromArray

/**
     * Rebuild a step from the journal row the plan repository read.
     *
     * @param   array<string, mixed>  $document  Stored step object, as written by `toArray()`.
     *
     * @return  self  The revalidated journal entry.
     *
     * @throws  InvalidBusinessSchema  When the document carries an unknown property, a field is absent
     *          or misshapen, the stored kind, risk, or state is not one this build knows, or a step
     *          invariant fails.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When a stored cursor or
     *          outcome cannot be canonically encoded.
     *
     * @since   2.0.0
     */

### pending

/**
     * Create the untouched journal entry the planner saves beside a newly planned operation.
     *
     * @param   string              $planId     Plan the step belongs to, as a UUID.
     * @param   SchemaOperation     $operation  Operation this step will execute; supplies the ordinal,
     *          kind, risk, and content address the journal records.
     * @param   ?DateTimeImmutable  $updatedAt  When the row was written, which the repository requires
     *          before it will persist the step.
     *
     * @return  self  A pending step carrying no execution state at all.
     *
     * @throws  InvalidBusinessSchema  When the plan ID is not a canonical UUID.
     *
     * @since   2.0.0
     */

### start

/**
     * Begin a fresh attempt on this step under the executor's current fence.
     *
     * The prior schema checksum is supplied rather than remembered, because a first attempt is measured
     * against the chain value the preceding steps produced. A failed step re-enters here, which clears
     * the recorded error and outcome so the retry is journaled from a clean slate.
     *
     * @param   int                $executionFence        Fence the executing run holds.
     * @param   string             $beforeSchemaChecksum  Schema checksum in effect before this step runs.
     * @param   DateTimeImmutable  $at                    Instant the attempt started.
     *
     * @return  self  A running step with the attempt counter advanced and the terminal state cleared.
     *
     * @throws  InvalidBusinessSchema  When the step is neither pending nor failed, the fence is not
     *          positive, or the prior checksum is not a lowercase SHA-256 digest.
     *
     * @since   2.0.0
     */

### resume

/**
     * Take over a step whose previous attempt was interrupted, under a new fence.
     *
     * Unlike `start()` the prior schema checksum is kept rather than supplied, so the recovering run
     * measures itself against the same starting point the abandoned attempt did, and any cursor already
     * reached is carried across so a long rewrite is not begun again from the top.
     *
     * @param   int                $executionFence  Fence the recovering run holds.
     * @param   DateTimeImmutable  $at              Instant the new attempt started.
     *
     * @return  self  A running step with the attempt counter advanced.
     *
     * @throws  InvalidBusinessSchema  When the step is neither running nor failed, it never recorded a
     *          prior schema checksum, or the fence is not positive.
     *
     * @since   2.0.0
     */

### checkpoint

/**
     * Record how far a chunked rewrite has durably progressed, without ending the attempt.
     *
     * The executor writes a checkpoint after each committed chunk, so an interruption resumes from the
     * last keyset position instead of rewriting rows that were already converted. The attempt counter
     * and start time are untouched: this is progress within one attempt, not a new one.
     *
     * @param   array<string, bool|int|string>  $cursor  Keyset the last committed chunk reached.
     * @param   DateTimeImmutable               $at      Instant the checkpoint was written.
     *
     * @return  self  The same running attempt with its cursor and update time advanced.
     *
     * @throws  InvalidBusinessSchema  When the step is not running, or the cursor is not a string-keyed
     *          object of bool, int, and string values.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the cursor cannot be
     *          canonically encoded.
     *
     * @since   2.0.0
     */

### complete

/**
     * Close the attempt as successful and record the schema checksum it produced.
     *
     * The resulting checksum is the value the next step will start from, so writing it here is what
     * extends the chain a resumed execution verifies itself against.
     *
     * @param   string                $afterSchemaChecksum  Schema checksum in effect after this step.
     * @param   array<string, mixed>  $outcome              Facts worth journaling about the attempt.
     * @param   DateTimeImmutable     $at                   Instant the step completed.
     *
     * @return  self  A completed step carrying its resulting checksum and outcome.
     *
     * @throws  InvalidBusinessSchema  When the step is not running, the resulting checksum is not a
     *          lowercase SHA-256 digest, or the outcome is not a string-keyed object.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the outcome cannot
     *          be canonically encoded.
     *
     * @since   2.0.0
     */

### fail

/**
     * Close the attempt as failed, leaving the step open to a further attempt.
     *
     * A failed step is not settled: `start()` and `resume()` both accept one, which is how operator
     * recovery picks execution back up. The cursor survives, so a rewrite that failed part way is not
     * repeated from the top, while the resulting checksum stays unset: the step reached no verified end
     * state, so there is nothing for a later step to chain onto.
     *
     * @param   string                $errorCode  Lowercase failure code of at most 64 characters.
     * @param   array<string, mixed>  $outcome    Facts worth journaling about the failed attempt.
     * @param   DateTimeImmutable     $at         Instant the attempt was abandoned.
     *
     * @return  self  A failed step carrying its error code and outcome.
     *
     * @throws  InvalidBusinessSchema  When the step is not running, the error code breaks its grammar,
     *          or the outcome is not a string-keyed object.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the outcome cannot
     *          be canonically encoded.
     *
     * @since   2.0.0
     */

### toArray

/**
     * Export the step in the document shape the plan repository persists.
     *
     * @return  array<string, mixed>  Every journal field, with the enums reduced to their backing
     *          values and the three timestamps rendered as UTC text or left null.
     *
     * @since   2.0.0
     */

## Kumwe\BusinessSchema\Domain\SchemaStepStatus

/**
 * Journal state of one schema-plan step, as persisted on that step's row.
 *
 * The executor writes this value before and after every attempt, and `SchemaPlanStep` decides which
 * evidence each state is allowed to carry: a pending step holds no execution state at all, an attempted
 * step must name its fence, start time, and prior checksum, and a terminal step must carry a completion
 * time. That is what lets an interrupted execution be resumed from the journal rather than replayed
 * from the beginning against a database that has already moved.
 *
 * @since  2.0.0
 */

### terminal

/**
     * Report whether the step is settled and no further attempt is owed for it.
     *
     * `Failed` is deliberately not settled: it is the state operator recovery restarts from, so it has
     * to remain distinguishable from a step that genuinely needs nothing more.
     *
     * @return  bool  True for completed, compensated, and skipped steps only.
     *
     * @since   2.0.0
     */

### cases

Generated enum/runtime member.

### from

Generated enum/runtime member.

### tryFrom

Generated enum/runtime member.

## Kumwe\BusinessSchema\Domain\SchemaDocument

/**
 * Shared field readers and identifier rules for every persisted business-schema document.
 *
 * Blueprints, plans, operations, and installations are all rebuilt from untrusted arrays — database rows,
 * API payloads, fixtures — so each `fromArray()` in this namespace reads its fields through these helpers
 * rather than testing types inline. That keeps one rejection vocabulary: a bad document always raises
 * `InvalidBusinessSchema` naming the offending property, and every identifier that will end up inside a
 * physical name or an SQL statement is checked against the same bounded grammar before it travels further.
 *
 * The class is a pure helper namespace: it carries no state and its private constructor blocks instances.
 *
 * @since  2.0.0
 */

### assertOnly

/**
     * Reject a document that carries any property outside the declared set.
     *
     * Documents are closed rather than tolerant, so an unrecognised key is treated as a version or
     * corruption problem instead of being silently dropped on the next round trip.
     *
     * @param   array<string, mixed>  $document  Decoded document to inspect.
     * @param   list<string>          $allowed   Property names this document shape declares.
     * @param   string                $subject   Sentence-leading name of the document, used in the failure message.
     *
     * @return  void
     *
     * @throws  InvalidBusinessSchema  When the document holds a property outside the allowed set.
     *
     * @since   2.0.0
     */

### string

/**
     * Read a required, non-blank string property.
     *
     * @param   array<string, mixed>  $document  Decoded document to read from.
     * @param   string                $key       Property name to read.
     *
     * @return  string  The value with surrounding whitespace removed.
     *
     * @throws  InvalidBusinessSchema  When the property is absent, not a string, or blank.
     *
     * @since   2.0.0
     */

### nullableString

/**
     * Read an optional string property that may be absent or explicitly null.
     *
     * @param   array<string, mixed>  $document  Decoded document to read from.
     * @param   string                $key       Property name to read.
     *
     * @return  string|null  The trimmed value, or null when the property is absent or null.
     *
     * @throws  InvalidBusinessSchema  When the property is present and non-null but is not a non-blank string.
     *
     * @since   2.0.0
     */

### integer

/**
     * Read a required integer property, refusing numeric strings and floats.
     *
     * @param   array<string, mixed>  $document  Decoded document to read from.
     * @param   string                $key       Property name to read.
     *
     * @return  int  The exact stored integer.
     *
     * @throws  InvalidBusinessSchema  When the property is absent or is not an integer.
     *
     * @since   2.0.0
     */

### nullableInteger

/**
     * Read an optional integer property that may be absent or explicitly null.
     *
     * @param   array<string, mixed>  $document  Decoded document to read from.
     * @param   string                $key       Property name to read.
     *
     * @return  int|null  The stored integer, or null when the property is absent or null.
     *
     * @throws  InvalidBusinessSchema  When the property is present and non-null but is not an integer.
     *
     * @since   2.0.0
     */

### boolean

/**
     * Read a boolean property, falling back to a caller-chosen default when it is absent.
     *
     * An explicit null is not treated as absence: it fails, because a flag that was persisted as null
     * means the document is malformed rather than defaulted.
     *
     * @param   array<string, mixed>  $document  Decoded document to read from.
     * @param   string                $key       Property name to read.
     * @param   bool                  $default   Value to use when the property is absent.
     *
     * @return  bool  The stored flag, or the default.
     *
     * @throws  InvalidBusinessSchema  When the property is present but is not a boolean.
     *
     * @since   2.0.0
     */

### object

/**
     * Read a nested object property as a string-keyed map.
     *
     * @param   array<string, mixed>  $document  Decoded document to read from.
     * @param   string                $key       Property name to read.
     * @param   bool                  $nullable  Whether an absent or null property is acceptable.
     *
     * @return  array<string, mixed>|null  The nested object, or null only when it was absent and $nullable
     *          was requested.
     *
     * @throws  InvalidBusinessSchema  When the property is not an array, is a non-empty list, or uses a
     *          non-string key; and when it is missing while $nullable is false.
     *
     * @since   2.0.0
     */

### objects

/**
     * Read a property holding a list of nested objects, as every collection field does.
     *
     * @param   array<string, mixed>  $document  Decoded document to read from.
     * @param   string                $key       Property name to read.
     *
     * @return  list<array<string, mixed>>  The entries in stored order; an empty list when the property
     *          holds an empty list.
     *
     * @throws  InvalidBusinessSchema  When the property is absent, is not a list, or holds an entry that
     *          is not a string-keyed object.
     *
     * @since   2.0.0
     */

### strings

/**
     * Read a property holding a list of non-blank strings, such as a key's column names.
     *
     * @param   array<string, mixed>  $document  Decoded document to read from.
     * @param   string                $key       Property name to read.
     *
     * @return  list<string>  The entries in stored order, each trimmed; order is significant for the
     *          column lists this reads.
     *
     * @throws  InvalidBusinessSchema  When the property is absent, is not a list, or holds a blank or
     *          non-string entry.
     *
     * @since   2.0.0
     */

### assertUuid

/**
     * Require a canonical UUID, as every definition and plan identity must be.
     *
     * @param   string  $value    Candidate identifier.
     * @param   string  $subject  Sentence-leading name of the field, used in the failure message.
     *
     * @return  void
     *
     * @throws  InvalidBusinessSchema  When the value is not a canonical UUID.
     *
     * @since   2.0.0
     */

### assertChecksum

/**
     * Require a lowercase hexadecimal SHA-256 digest, optionally allowing its absence.
     *
     * Checksums are the only thing binding an approval, an installation, and a live schema together, so a
     * digest in an unexpected case or length is rejected rather than normalized.
     *
     * @param   string|null  $value     Candidate digest.
     * @param   string       $subject   Sentence-leading name of the field, used in the failure message.
     * @param   bool         $nullable  Whether a null digest is a legitimate "not recorded" value.
     *
     * @return  void
     *
     * @throws  InvalidBusinessSchema  When the value is not 64 lowercase hexadecimal characters, or is
     *          null while $nullable is false.
     *
     * @since   2.0.0
     */

### assertIdentifier

/**
     * Require a metadata identifier: lowercase alphanumeric groups joined by `.`, `_`, `:`, or `-`.
     *
     * These are the logical handles a plan refers to — table, column, index, and foreign-key names inside
     * the blueprint — so the grammar stays narrow enough to be safe in messages, keys, and comparisons.
     *
     * @param   string  $value    Candidate identifier.
     * @param   string  $subject  Sentence-leading name of the field, used in the failure message.
     * @param   int     $maximum  Longest accepted length in bytes.
     *
     * @return  void
     *
     * @throws  InvalidBusinessSchema  When the value breaks the grammar or exceeds the length budget.
     *
     * @since   2.0.0
     */

### assertBoundedText

/**
     * Require free text that is present, length bounded, and free of control characters.
     *
     * Use this for operator-facing values such as an owner or approver identity, which are stored and
     * echoed back but are not identifiers.
     *
     * @param   string  $value    Candidate text.
     * @param   string  $subject  Sentence-leading name of the field, used in the failure message.
     * @param   int     $maximum  Longest accepted length in bytes.
     *
     * @return  void
     *
     * @throws  InvalidBusinessSchema  When the value is empty, too long, or carries control characters.
     *
     * @since   2.0.0
     */

### assertObjectValue

/**
     * Require an already-decoded array to be a string-keyed object rather than a list.
     *
     * An empty array passes, because JSON cannot distinguish `[]` from `{}` once decoded.
     *
     * @param   array<array-key, mixed>  $value    Decoded value to classify.
     * @param   string                   $subject  Sentence-leading name of the field, used in the failure message.
     *
     * @return  void
     *
     * @throws  InvalidBusinessSchema  When the value is a non-empty list or holds a non-string key.
     *
     * @since   2.0.0
     */

### assertPhysicalIdentifier

/**
     * Require a name that is legal as a physical table, column, index, or constraint identifier.
     *
     * The 63-byte ceiling and the lowercase letter, digit, underscore alphabet are PostgreSQL's limit
     * applied everywhere, so one compiled blueprint installs unchanged on every supported engine.
     *
     * @param   string  $value    Candidate physical name.
     * @param   string  $subject  Sentence-leading name of the field, used in the failure message.
     *
     * @return  void
     *
     * @throws  InvalidBusinessSchema  When the name is over 63 bytes or outside the portable alphabet.
     *
     * @since   2.0.0
     */

### date

/**
     * Parse a stored timestamp into UTC.
     *
     * @param   string  $value    Timestamp text as persisted.
     * @param   string  $subject  Sentence-leading name of the field, used in the failure message.
     *
     * @return  DateTimeImmutable  The instant converted to UTC, whatever offset it was written with.
     *
     * @throws  InvalidBusinessSchema  When the text is not a readable date and time.
     *
     * @since   2.0.0
     */

### formatDate

/**
     * Render an instant in the one timestamp format every schema document persists.
     *
     * Because the format is fixed-width UTC with microseconds, stored timestamps sort lexically in the
     * same order they occurred, and a document round trip is byte stable.
     *
     * @param   DateTimeInterface  $value  Instant to render, in any timezone.
     *
     * @return  string  UTC timestamp as `Y-m-d\TH:i:s.u\Z`.
     *
     * @since   2.0.0
     */

## Kumwe\BusinessSchema\Domain\SchemaPlan

/**
 * One approvable, executable migration of a business definition's physical schema on one site.
 *
 * A plan is the unit the whole schema pipeline is built around: the planner compiles the difference between
 * two definition versions into an ordered set of `SchemaOperation` steps, an administrator approves that
 * exact content, and only then may an executor run DDL. Everything needed to make those three stages safe
 * across processes lives here — the definition versions and checksums the plan moves between, the physical
 * schema checksum it expects to find and the one it must arrive at, the approval evidence, the recovery
 * evidence a locking or destructive plan requires, the monotonic execution fence, and the recorded outcome.
 *
 * The plan is immutable and content addressed. `checksum()` covers the operations and the bindings but not
 * the lifecycle state, so re-planning invalidates an existing approval while a status change does not; the
 * constructor refuses an approval whose checksum is not this plan's. Every transition returns a new instance
 * with an incremented revision, and the constructor re-proves the whole state machine, so a plan loaded from
 * the database cannot carry an evidence combination the transitions would never have produced.
 *
 * @since  2.0.0
 */

### __construct

/**
     * Assemble a plan and prove its bindings, ordering, risk, and lifecycle evidence all agree.
     *
     * @param   string                     $id                      UUID identifying this plan.
     * @param   string                     $definitionId            UUID of the definition being changed.
     * @param   string                     $siteIdentifier          Site whose installed tables it acts on.
     * @param   int|null                   $fromDefinitionVersion   Version upgraded from, null on first install.
     * @param   int                        $toDefinitionVersion     Version installed; above the prior one.
     * @param   string|null                $fromDefinitionChecksum  SHA-256 of the prior definition version.
     * @param   string                     $toDefinitionChecksum    SHA-256 of the definition being installed.
     * @param   string|null                $fromSchemaChecksum      Physical schema the plan expects to find.
     * @param   string                     $targetSchemaChecksum    Physical schema it must arrive at.
     * @param   list<SchemaOperation>      $operations              Steps in any order; sorted by ordinal here.
     * @param   SchemaRisk                 $risk                    Impact class; the highest a step declares.
     * @param   SchemaPlanStatus           $status                  Lifecycle position the evidence must fit.
     * @param   int                        $revision                Optimistic-locking revision, from one.
     * @param   string                     $createdBy               Identity of the actor who planned it.
     * @param   DateTimeImmutable          $createdAt               Instant the plan was compiled.
     * @param   SchemaPlanApproval|null    $approval                Approval bound to this plan's checksum.
     * @param   string|null                $recoveryEvidenceId      UUID of the backing restore drill.
     * @param   int|null                   $executionFence          Fence held by the executor running it.
     * @param   array<string, mixed>|null  $outcome                 Result; only a terminal status has one.
     * @param   DateTimeImmutable|null     $updatedAt               Transition time; defaults to $createdAt.
     *
     * @throws  InvalidBusinessSchema  When an identifier or checksum is malformed, the version bounds are not
     *          ascending, the prior version and checksum are not both present or both
     *          absent, more than 10000 operations are supplied, their ordinals are not
     *          contiguous from one, the risk is not the highest one declared, the
     *          revision is below one, the fence is below one, the outcome is not a
     *          string-keyed object, the status carries evidence it may not or omits
     *          evidence it must, the approval is bound to a different canonical plan,
     *          or the update time precedes the creation time.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the outcome holds a value
     *          that cannot be canonically encoded, such as a float or an object, or an approved plan carries
     *          more than 512 operations, which the canonical encoder refuses to fingerprint.
     *
     * @since   2.0.0
     */

### fromArray

/**
     * Rebuild a plan from its persisted document and confirm it was not tampered with.
     *
     * When the stored document carries a `plan_checksum`, it is compared against the checksum recomputed
     * from the decoded content, so an edited plan row is refused rather than approved or executed.
     *
     * @param   array<string, mixed>  $document  Stored plan object, as written by `toArray()`.
     *
     * @return  self  The revalidated plan, with both timestamps normalized to UTC.
     *
     * @throws  InvalidBusinessSchema  When the document carries an unknown property, a field is missing or
     *          misshapen, the stored risk or status is not a known one, any plan
     *          invariant fails, or the stored checksum does not match the content.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the stored outcome holds
     *          a value that cannot be canonically encoded, or the document holds more than 512 operations.
     *
     * @since   2.0.0
     */

### operations

/**
     * List the steps the executor walks.
     *
     * @return  list<SchemaOperation>  The operations in ordinal order, so a step's position in this list is
     *          one less than its ordinal.
     *
     * @since   2.0.0
     */

### approve

/**
     * Bind approval evidence to this exact plan so an executor may run it.
     *
     * The caller passes back the checksum it showed the approver, and approval is refused unless it still
     * matches; that is what stops a plan re-compiled between inspection and approval from inheriting
     * someone's consent. Risk decides what else the approver had to supply: every class but an online-safe
     * addition needs a step-up confirmation, and a locking or destructive one needs a restore drill.
     *
     * @param   string             $actorIdentifier     Bounded identity of the approving administrator.
     * @param   DateTimeImmutable  $approvedAt          Instant of approval, also recorded as the update time.
     * @param   string             $expectedChecksum    Plan checksum the approver was shown.
     * @param   string|null        $confirmationDigest  Step-up confirmation, required above online-safe additive.
     * @param   string|null        $recoveryEvidenceId  Restore-drill UUID, required for locking and destructive
     *          plans.
     *
     * @return  self  An approved copy at the next revision, carrying the new approval evidence.
     *
     * @throws  InvalidBusinessSchema  When the plan is not pending approval, the checksum no longer matches,
     *          a required confirmation digest or recovery evidence is absent, or the
     *          approval evidence itself is malformed.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the plan holds more than
     *          512 operations, which the canonical encoder refuses to fingerprint.
     *
     * @since   2.0.0
     */

### begin

/**
     * Take an approved plan into execution under the fence of the lock the executor holds.
     *
     * The fence is recorded on the plan so writes can be gated on it: the plan repository matches the stored
     * fence when replacing a row, so an executor whose lock was taken over fails its write rather than
     * overwriting the newer owner's work.
     *
     * @param   int                $fence  Monotonic fence issued with the definition lock; must be positive.
     * @param   DateTimeImmutable  $at     Instant to record as the update time.
     *
     * @return  self  An executing copy at the next revision, holding the fence and no outcome.
     *
     * @throws  InvalidBusinessSchema  When the plan is not approved, or the fence is below one.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the plan holds more than
     *          512 operations, which the canonical encoder refuses to fingerprint.
     *
     * @since   2.0.0
     */

### resume

/**
     * Put an interrupted plan back into execution under a freshly issued fence.
     *
     * This is the recovery counterpart to `begin()`: it accepts a plan that is still marked executing after
     * a crash, as well as one that stopped on a failure or was held for operator inspection, and clears the
     * recorded outcome so the resumed run records its own. The new fence supersedes the abandoned one.
     *
     * @param   int                $fence  Monotonic fence issued with the definition lock; must be positive.
     * @param   DateTimeImmutable  $at     Instant to record as the update time.
     *
     * @return  self  An executing copy at the next revision, holding the new fence and no outcome.
     *
     * @throws  InvalidBusinessSchema  When the plan is not executing, failed, or awaiting recovery, or the
     *          fence is below one.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the plan holds more than
     *          512 operations, which the canonical encoder refuses to fingerprint.
     *
     * @since   2.0.0
     */

### complete

/**
     * Settle an executing plan as completed and store the executor's report of the run.
     *
     * The plan does not check that the live schema actually reached `targetSchemaChecksum`; the executor
     * proves that by introspection before it calls this, and this only guards the status it transitions from.
     *
     * @param   array<string, mixed>  $outcome  Execution report to store, as the executor summarised the run.
     * @param   DateTimeImmutable     $at       Instant to record as the update time.
     *
     * @return  self  A completed copy at the next revision, keeping the fence the run held.
     *
     * @throws  InvalidBusinessSchema  When the plan is not currently executing.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the outcome holds a value
     *          that cannot be canonically encoded, such as a float or an object.
     *
     * @since   2.0.0
     */

### fail

/**
     * Record that execution stopped on a known error while the physical state is still understood.
     *
     * This and `recoveryRequired()` both stop an executing plan on a code and both leave it resumable; the
     * difference is the signal. Failed says the journal still explains where the database stands, so a
     * retry needs no inspection first. The code is merged into the stored outcome under `error_code`,
     * overriding any entry of that name the caller supplied.
     *
     * @param   string                $errorCode  Lowercase dotted code naming the failure, at most 64 bytes.
     * @param   array<string, mixed>  $outcome    Execution report to store alongside the code.
     * @param   DateTimeImmutable     $at         Instant to record as the update time.
     *
     * @return  self  A failed copy at the next revision, keeping the fence the run held.
     *
     * @throws  InvalidBusinessSchema  When the plan is not currently executing, or the error code is outside
     *          its grammar.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the outcome holds a value
     *          that cannot be canonically encoded, such as a float or an object.
     *
     * @since   2.0.0
     */

### recoveryRequired

/**
     * Park the plan where the journal may no longer describe the live schema, pending operator judgement.
     *
     * This is what `BusinessSchemaExecutor` records whenever a run is interrupted, because a step that threw
     * mid-statement may have left the database on either side of it. Mechanically it is `fail()` under a
     * different status, including the code merged into the outcome under `error_code`.
     *
     * @param   string                $errorCode  Lowercase dotted code naming the failure, at most 64 bytes.
     * @param   array<string, mixed>  $outcome    Everything known about where execution stopped.
     * @param   DateTimeImmutable     $at         Instant to record as the update time.
     *
     * @return  self  A recovery-required copy at the next revision, keeping the fence the run held.
     *
     * @throws  InvalidBusinessSchema  When the plan is not currently executing, or the error code is outside
     *          its grammar.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the outcome holds a value
     *          that cannot be canonically encoded, such as a float or an object.
     *
     * @since   2.0.0
     */

### compensate

/**
     * Close out an interrupted plan once its partial effects have been resolved.
     *
     * This is the terminal status an interrupted plan reaches once its partial effects have been undone or
     * reconciled. It settles the plan without claiming the migration succeeded, which is what separates a
     * resolved failure from one still waiting on someone.
     *
     * @param   array<string, mixed>  $outcome  Report of what the compensation actually did.
     * @param   DateTimeImmutable     $at       Instant to record as the update time.
     *
     * @return  self  A compensated copy at the next revision, keeping the fence the interrupted run held.
     *
     * @throws  InvalidBusinessSchema  When the plan is neither failed nor awaiting recovery.
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the outcome holds a value
     *          that cannot be canonically encoded, such as a float or an object.
     *
     * @since   2.0.0
     */

### canonicalPlan

/**
     * Export the content that defines what this plan would do, without any of its lifecycle state.
     *
     * Identity, status, revision, and every piece of execution evidence are left out on purpose: this is
     * the array `checksum()` fingerprints, so moving the plan through its lifecycle never disturbs the
     * value an approval was bound to, while any change to the operations or the bindings does.
     *
     * @return  array<string, mixed>  The definition and schema bindings, the operations as documents in
     *          ordinal order, and the plan's risk.
     *
     * @since   2.0.0
     */

### checksum

/**
     * Compute the content address an approval binds to and a reloaded plan is re-verified against.
     *
     * @return  string  Lowercase SHA-256 over the canonical JSON encoding of `canonicalPlan()`.
     *
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the plan holds more than
     *          512 operations, which the canonical encoder refuses to fingerprint.
     *
     * @since   2.0.0
     */

### toArray

/**
     * Export the whole plan in the shape persisted in the plan table and served to the API.
     *
     * The `plan_checksum` entry is recomputed on every export rather than carried as state, which is what
     * lets `fromArray()` catch a stored document that was edited underneath the application.
     *
     * @return  array<string, mixed>  The canonical plan plus identity, status, revision, creator, timestamps,
     *          execution evidence, and the recomputed `plan_checksum`.
     *
     * @throws  \Kumwe\BusinessDefinition\Domain\InvalidBusinessDefinition  When the plan holds more than
     *          512 operations, which the canonical encoder refuses to fingerprint.
     *
     * @since   2.0.0
     */

## Kumwe\BusinessSchema\Planner\SchemaChangePlanner

/** Pure ordered schema difference compiler. Supplied snapshots are already authorized by the host. */

### operations

/**
     * @param array<string, PhysicalSchemaBlueprint> $dependencyBlueprints Pinned dependency schemas.
     * @return list<SchemaOperation> Deterministically ordered operations; never executes DDL.
     */

### containsPinnedRowBreakingChange

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

### hasRecordRepin

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

### dependencyHandles

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

## Kumwe\BusinessSchema\Container\PhysicalSchemaCompilerFactory

/** Constructs the compiler only with explicit trusted host inputs. No registry or prefix is invented. */

### __invoke

/** @throws UnexpectedValueException When an advertised host binding has the wrong runtime type. */

## Kumwe\BusinessSchema\Container\SchemaChangePlannerFactory

/** Constructs the stateless planner; immutable input snapshots are supplied to each operation. */

### __invoke

/** @return SchemaChangePlanner Fresh stateless planner; no transaction or host context is captured. */

## Kumwe\BusinessSchema\Contract\DefinitionSchemaLookup

/** Host-supplied immutable trusted-definition lookup; no authorization or persistence is implemented here. */

### published

/** Resolve a published definition in the explicit site at the required generation, or return null. */

