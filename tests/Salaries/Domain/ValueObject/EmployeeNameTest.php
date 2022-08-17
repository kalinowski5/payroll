<?php
declare(strict_types=1);

namespace XYZ\Tests\Salaries\Domain\ValueObject;

use PHPUnit\Framework\TestCase;
use XYZ\Salaries\Domain\ValueObject\EmployeeName;

class EmployeeNameTest extends TestCase
{
    public function testItCanBeConvertedToString(): void
    {
        $name = new EmployeeName('John', 'Doe');

        self::assertSame('John Doe', (string) $name);
    }
}
