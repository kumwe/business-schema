<?php

declare(strict_types=1);

use Kumwe\BusinessDefinition\Application\FieldTypeDefinitionResolver;
use Kumwe\BusinessDefinition\Application\FieldTypeRegistry;
use Kumwe\BusinessDefinition\Domain\EntityTypeDefinition;
use Kumwe\BusinessSchema\Compiler\CanonicalDefinitionPhysicalSchemaCompiler;
use Kumwe\BusinessSchema\ConfigProvider;
use Kumwe\BusinessSchema\Contract\DefinitionSchemaLookup;
use Kumwe\BusinessSchema\Domain\PhysicalNameCompiler;
use Kumwe\BusinessSchema\Planner\SchemaChangePlanner;
use Laminas\ServiceManager\ServiceManager;

require $argv[1] ?? dirname(__DIR__) . '/vendor/autoload.php';

// This example host has no published related definitions. A real host binds its trusted lookup.
$config = (new ConfigProvider())()['dependencies'];
$config['services'] = [
    DefinitionSchemaLookup::class => new class implements DefinitionSchemaLookup {
        public function published(
            string $siteIdentifier,
            string $handle,
            ?int $version = null,
        ): ?EntityTypeDefinition {
            return null;
        }
    },
    FieldTypeDefinitionResolver::class => new FieldTypeRegistry(),
    PhysicalNameCompiler::class => new PhysicalNameCompiler('kb_'),
];
$container = new ServiceManager($config);
$compiler = $container->get(CanonicalDefinitionPhysicalSchemaCompiler::class);
$planner = $container->get(SchemaChangePlanner::class);
if (
    !$compiler instanceof CanonicalDefinitionPhysicalSchemaCompiler || !$planner instanceof SchemaChangePlanner
    || $compiler !== $container->get(CanonicalDefinitionPhysicalSchemaCompiler::class)
    || $planner !== $container->get(SchemaChangePlanner::class)
) {
    throw new RuntimeException('The package must resolve shared compiler and planner services.');
}
echo "Archive consumer resolved both shared services using explicit host bindings.\n";
