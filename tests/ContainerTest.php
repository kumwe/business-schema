<?php

declare(strict_types=1);

namespace Kumwe\BusinessSchema\Tests;

use Kumwe\BusinessDefinition\Application\FieldTypeDefinitionResolver;
use Kumwe\BusinessDefinition\Application\FieldTypeRegistry;
use Kumwe\BusinessDefinition\Domain\EntityTypeDefinition;
use Kumwe\BusinessSchema\Compiler\CanonicalDefinitionPhysicalSchemaCompiler;
use Kumwe\BusinessSchema\ConfigProvider;
use Kumwe\BusinessSchema\Contract\DefinitionSchemaLookup;
use Kumwe\BusinessSchema\Domain\PhysicalNameCompiler;
use Kumwe\BusinessSchema\Planner\SchemaChangePlanner;
use Laminas\ServiceManager\ServiceManager;
use PHPUnit\Framework\TestCase;

final class ContainerTest extends TestCase
{
    public function testRealContainerResolvesSharedCompilerWithExplicitHostPorts(): void
    {
        $config = (new ConfigProvider())()['dependencies'];
        $config['services'] = [
            DefinitionSchemaLookup::class => new class implements DefinitionSchemaLookup {
                public function published(
                    string $siteIdentifier,
                    string $handle,
                    ?int $version = null
                ): ?EntityTypeDefinition {
                    return null;
                }
            },
            FieldTypeDefinitionResolver::class => new FieldTypeRegistry(),
            PhysicalNameCompiler::class => new PhysicalNameCompiler('kb_'),
        ];
        $container = new ServiceManager($config);
        self::assertInstanceOf(CanonicalDefinitionPhysicalSchemaCompiler::class, $container->get(
            CanonicalDefinitionPhysicalSchemaCompiler::class,
        ));
        self::assertSame(
            $container->get(CanonicalDefinitionPhysicalSchemaCompiler::class),
            $container->get(CanonicalDefinitionPhysicalSchemaCompiler::class),
        );
        self::assertInstanceOf(SchemaChangePlanner::class, $container->get(SchemaChangePlanner::class));
        self::assertFalse($container->has('database'));
    }

    public function testMissingHostAuthorityBindingDoesNotReceiveAnImplicitDefault(): void
    {
        $container = new ServiceManager((new ConfigProvider())()['dependencies']);
        $this->expectException(\Laminas\ServiceManager\Exception\ServiceNotFoundException::class);
        $container->get(CanonicalDefinitionPhysicalSchemaCompiler::class);
    }
}
