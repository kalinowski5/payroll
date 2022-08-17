<?php
declare(strict_types=1);

namespace XYZ\Tests\Salaries\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use XYZ\Salaries\Domain\ValueObject\PercentageSupplement;

class PercentageSupplementTest extends TestCase
{
    public function testItCanBeCreated(): void
    {
        $supplement = new PercentageSupplement(25);

        self::assertSame(25, $supplement->value());
    }

    public function testItCannotBeSmallerThanZero(): void
    {
        self::expectExceptionObject(new \InvalidArgumentException('Value of Percentage Supplement must be greater than zero'));

        new PercentageSupplement(-1);
    }
}
