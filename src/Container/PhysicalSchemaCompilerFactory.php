<?php

declare(strict_types=1);

namespace Kumwe\BusinessSchema\Container;

use Kumwe\BusinessDefinition\Application\FieldTypeDefinitionResolver;
use Kumwe\BusinessSchema\Compiler\CanonicalDefinitionPhysicalSchemaCompiler;
use Kumwe\BusinessSchema\Contract\DefinitionSchemaLookup;
use Kumwe\BusinessSchema\Domain\PhysicalNameCompiler;
use Psr\Container\ContainerInterface;
use UnexpectedValueException;

/** Constructs the compiler only with explicit trusted host inputs. No registry or prefix is invented. */
final class PhysicalSchemaCompilerFactory
{
    /** @throws UnexpectedValueException When an advertised host binding has the wrong runtime type. */
    public function __invoke(ContainerInterface $container): CanonicalDefinitionPhysicalSchemaCompiler
    {
        $definitions = $container->get(DefinitionSchemaLookup::class);
        $fields = $container->get(FieldTypeDefinitionResolver::class);
        $names = $container->get(PhysicalNameCompiler::class);
        if (
            !$definitions instanceof DefinitionSchemaLookup || !$fields instanceof FieldTypeDefinitionResolver
            || !$names instanceof PhysicalNameCompiler
        ) {
            throw new UnexpectedValueException('Schema compilation requires explicit typed host bindings.');
        }
        return new CanonicalDefinitionPhysicalSchemaCompiler($definitions, $fields, $names);
    }
}
