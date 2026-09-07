<?php

declare(strict_types=1);

namespace Kumwe\BusinessSchema;

use Kumwe\BusinessSchema\Compiler\CanonicalDefinitionPhysicalSchemaCompiler;
use Kumwe\BusinessSchema\Container\PhysicalSchemaCompilerFactory;
use Kumwe\BusinessSchema\Container\SchemaChangePlannerFactory;
use Kumwe\BusinessSchema\Planner\SchemaChangePlanner;

/** Deterministic service declarations; trusted lookup and naming configuration are explicit host inputs. */
final class ConfigProvider
{
    /**
     * @return array{dependencies: array{factories: array<class-string, class-string>, shared: array<class-string,
     * bool>}}
     */
    public function __invoke(): array
    {
        return ['dependencies' => [
            'factories' => [
                CanonicalDefinitionPhysicalSchemaCompiler::class => PhysicalSchemaCompilerFactory::class,
                SchemaChangePlanner::class => SchemaChangePlannerFactory::class,
            ],
            'shared' => [CanonicalDefinitionPhysicalSchemaCompiler::class => true, SchemaChangePlanner::class => true],
        ]];
    }
}
