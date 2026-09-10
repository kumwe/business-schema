<?php

declare(strict_types=1);

namespace Kumwe\BusinessSchema\Tests;

use Kumwe\BusinessDefinition\Domain\EntityTypeDefinition;
use Kumwe\BusinessSchema\Planner\SchemaChangePlanner;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SchemaChangePlanner::class)]
final class SchemaChangePlannerTest extends TestCase
{
    public function testDependencyHandlesIncludeAllReferenceFormsInStableUniqueOrder(): void
    {
        $document = self::document();
        $document['relationships'] = [
            ['handle' => 'supplier', 'label' => 'Supplier', 'kind' => 'many_to_one',
                'target' => 'site.default.supplier', 'on_delete' => 'restrict'],
            ['handle' => 'asset', 'label' => 'Asset', 'kind' => 'many_to_one',
                'target' => 'site.default.asset', 'on_delete' => 'restrict'],
        ];
        $document['fields'] = [...$document['fields'],
            ['handle' => 'owner', 'label' => 'Owner', 'type' => 'core.entity_reference',
                'configuration' => ['target' => 'site.default.contact']],
            ['handle' => 'lines', 'label' => 'Lines', 'type' => 'core.ordered_lines',
                'configuration' => ['target' => 'site.default.asset']],
            ['handle' => 'ignored', 'label' => 'Ignored', 'type' => 'core.text',
                'configuration' => ['target' => 'site.default.unrelated']],
        ];
        $planner = new SchemaChangePlanner();
        $expected = ['site.default.asset', 'site.default.contact', 'site.default.supplier'];

        self::assertSame($expected, $planner->dependencyHandles(EntityTypeDefinition::fromArray($document)));
        $document['relationships'] = array_reverse($document['relationships']);
        $document['fields'] = array_reverse($document['fields']);
        self::assertSame($expected, $planner->dependencyHandles(EntityTypeDefinition::fromArray($document)));
    }

    public function testDependencyHandlesIgnoreMissingAndNonStringTargets(): void
    {
        $document = self::document();
        $document['fields'] = [...$document['fields'],
            ['handle' => 'missing', 'label' => 'Missing', 'type' => 'core.entity_reference'],
            ['handle' => 'invalid', 'label' => 'Invalid', 'type' => 'core.ordered_lines',
                'configuration' => ['target' => 42]],
        ];

        self::assertSame([], (new SchemaChangePlanner())->dependencyHandles(
            EntityTypeDefinition::fromArray($document),
        ));
        self::assertSame([], (new SchemaChangePlanner())->dependencyHandles(
            EntityTypeDefinition::fromArray(self::document()),
        ));
    }

    /** @return array<string, mixed> */
    private static function document(): array
    {
        return [
            'id' => '018f4f24-98d8-7ad4-8f3f-38c909178b6b',
            'owner' => ['type' => 'site', 'identifier' => 'default'],
            'site' => 'default',
            'handle' => 'site.default.invoice',
            'singular_label' => 'Invoice',
            'plural_label' => 'Invoices',
            'status' => 'published',
            'definition_version' => 1,
            'storage_mode' => 'relational',
            'identity_strategy' => 'uuid',
            'scope' => 'site',
            'audit_enabled' => true,
            'revisions_enabled' => true,
            'fields' => [
                ['handle' => 'id', 'label' => 'Identity', 'type' => 'core.uuid',
                    'required' => true, 'nullable' => false, 'unique' => true, 'indexed' => true,
                    'immutable_after_create' => true, 'server_only' => true, 'read_only' => true],
            ],
            'relationships' => [],
            'views' => [],
            'actions' => [],
            'workflow' => null,
            'compatibility_metadata' => [],
        ];
    }
}
