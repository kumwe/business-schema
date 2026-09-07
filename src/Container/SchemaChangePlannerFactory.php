<?php

declare(strict_types=1);

namespace Kumwe\BusinessSchema\Container;

use Kumwe\BusinessSchema\Planner\SchemaChangePlanner;
use Psr\Container\ContainerInterface;

/** Constructs the stateless planner; immutable input snapshots are supplied to each operation. */
final class SchemaChangePlannerFactory
{
    /** @return SchemaChangePlanner Fresh stateless planner; no transaction or host context is captured. */
    public function __invoke(ContainerInterface $container): SchemaChangePlanner
    {
        return new SchemaChangePlanner();
    }
}
