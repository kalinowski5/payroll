<?php
declare(strict_types=1);

namespace XYZ\Tests\Salaries\Domain\Entity;

use Money\Money;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Uuid;
use XYZ\Salaries\Domain\Entity\Department;
use XYZ\Salaries\Domain\Entity\Employee;
use XYZ\Salaries\Domain\ValueObject\BaseSalary;
use XYZ\Salaries\Domain\ValueObject\EmployeeName;
use XYZ\Salaries\Domain\ValueObject\PercentageSupplement;

class PercentageSupplementTest extends TestCase
{
    /**
     * @dataProvider provideTestCases
     */
    public function testPercentageSupplement(BaseSalary $baseSalary, PercentageSupplement $percentageSupplement, Money $expectedTotalSalary): void
    {
        $department = new Department(Uuid::fromString('867f03c4-edb6-47cc-9ea7-2f4f43b62be1'), 'Customer Service', $percentageSupplement);

        $employee = new Employee(
            Uuid::fromString('e3bfc3d9-cf7c-405e-84a2-0a1a9bed6539'),
            new EmployeeName('Anna', 'Nowak'),
            new \DateTimeImmutable('2017-08-01'),
            $department,
            $baseSalary,
        );

        $actualTotalSalary = $employee->totalSalaryAt(new \DateTimeImmutable('2022-08-15'));

        self::assertEquals($expectedTotalSalary, $actualTotalSalary);
    }

    /**
     * @return array<array{BaseSalary,PercentageSupplement,Money}>
     */
    public function provideTestCases(): array
    {
        return [
            [new BaseSalary(Money::USD(0_00)), new PercentageSupplement(0), Money::USD(0_00)],
            [new BaseSalary(Money::USD(0_00)), new PercentageSupplement(10), Money::USD(0_00)],
            [new BaseSalary(Money::USD(1100_00)), new PercentageSupplement(0), Money::USD(1100_00)],
            [new BaseSalary(Money::USD(1100_00)), new PercentageSupplement(10), Money::USD(1210_00)],
            [new BaseSalary(Money::EUR(1100_00)), new PercentageSupplement(10), Money::EUR(1210_00)],
            [new BaseSalary(Money::USD(5000_00)), new PercentageSupplement(25), Money::USD(6250_00)],
            [new BaseSalary(Money::USD(1000_00)), new PercentageSupplement(100), Money::USD(2000_00)],
        ];
    }

}
