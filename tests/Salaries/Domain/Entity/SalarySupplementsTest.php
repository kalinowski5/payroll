<?php
declare(strict_types=1);

namespace XYZ\Tests\Salaries\Domain\Entity;

use PHPUnit\Framework\TestCase;
use XYZ\Salaries\Domain\Entity\Department;
use XYZ\Salaries\Domain\Entity\Employee;
use XYZ\Salaries\Domain\ValueObject\PercentageSupplement;

class SalarySupplementsTest extends TestCase
{
    public function testSalarySupplement(): void
    {
        $customerServiceDepartment = new Department('Customer Service', new PercentageSupplement(10));
        $employee = new Employee(
            'e3bfc3d9-cf7c-405e-84a2-0a1a9bed6539',
            new \DateTimeImmutable('2020-01-01'),
            $customerServiceDepartment,
            1100_00
        );

        //@TODO: use data provider
        self::assertEquals(1210_00, $employee->totalSalaryAt(new \DateTimeImmutable('2020-02-01')));
    }
}
