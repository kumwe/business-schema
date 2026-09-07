<?php

declare(strict_types=1);

namespace Kumwe\BusinessSchema\Tests;

use PHPUnit\Framework\TestCase;

final class ValueImmutabilityTest extends TestCase
{
    public function testSchemaOperationDetachesApprovedBeforeAndAfterStates(): void
    {
        $value = 'approved';
        $after = ['options' => ['label' => &$value]];
        $operation = new \Kumwe\BusinessSchema\Domain\SchemaOperation(
            1,
            \Kumwe\BusinessSchema\Domain\SchemaOperationKind::AddColumn,
            \Kumwe\BusinessSchema\Domain\SchemaRisk::OnlineSafeAdditive,
            'record',
            'field',
            after: $after
        );
        $checksum = $operation->checksum();
        $value = 'changed';
        self::assertSame($checksum, $operation->checksum());
        self::assertSame('approved', $operation->after['options']['label']);
    }
}
