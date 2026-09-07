<?php

declare(strict_types=1);

namespace Kumwe\BusinessSchema\Contract;

use Kumwe\BusinessDefinition\Domain\EntityTypeDefinition;

/** Host-supplied immutable trusted-definition lookup; no authorization or persistence is implemented here. */
interface DefinitionSchemaLookup
{
    /** Resolve a published definition in the explicit site at the required generation, or return null. */
    public function published(string $siteIdentifier, string $handle, ?int $version = null): ?EntityTypeDefinition;
}
