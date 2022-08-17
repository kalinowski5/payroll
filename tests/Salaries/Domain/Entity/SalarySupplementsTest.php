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
use XYZ\Salaries\Domain\ValueObject\SenioritySupplement;

class SalarySupplementsTest extends TestCase
{
    /**
     * @return array<string,array{BaseSalary,SenioritySupplement,\DateTimeImmutable,\DateTimeImmutable,Money}>
     */
    public function provideSenioritySupplementCases(): array
    {
        return [
            '15 years of employment with base salary 1000,00 USD' => [
                new BaseSalary(Money::USD(1000_00)),
                new SenioritySupplement(Money::USD(100_00)),
                new \DateTimeImmutable('01-08-2000'),
                new \DateTimeImmutable('02-08-2015'),
                Money::USD(2000_00),
            ],
            '11 years of employment with base salary 1000,00 USD' => [
                new BaseSalary(Money::USD(1000_00)),
                new SenioritySupplement(Money::USD(100_00)),
                new \DateTimeImmutable('01-08-2000'),
                new \DateTimeImmutable('02-08-2011'),
                Money::USD(2000_00),
            ],
            '11 years of employment with base salary 1500,00 USD' => [
                new BaseSalary(Money::USD(1500_00)),
                new SenioritySupplement(Money::USD(100_00)),
                new \DateTimeImmutable('01-08-2000'),
                new \DateTimeImmutable('02-08-2011'),
                Money::USD(2500_00),
            ],
            '10 years of employment with base salary 1000,00 USD' => [
                new BaseSalary(Money::USD(1000_00)),
                new SenioritySupplement(Money::USD(100_00)),
                new \DateTimeImmutable('01-08-2000'),
                new \DateTimeImmutable('02-08-2010'),
                Money::USD(2000_00),
            ],
            '9 years of employment with base salary 1000,00 USD' => [
                new BaseSalary(Money::USD(1000_00)),
                new SenioritySupplement(Money::USD(100_00)),
                new \DateTimeImmutable('01-08-2000'),
                new \DateTimeImmutable('02-08-2009'),
                Money::USD(1900_00),
            ],
            '1 year of employment with base salary 1000,00 USD' => [
                new BaseSalary(Money::USD(1000_00)),
                new SenioritySupplement(Money::USD(100_00)),
                new \DateTimeImmutable('01-08-2000'),
                new \DateTimeImmutable('02-08-2001'),
                Money::USD(1100_00),
            ],
            '11th month of employment with base salary 1000,00 USD' => [
                new BaseSalary(Money::USD(1000_00)),
                new SenioritySupplement(Money::USD(100_00)),
                new \DateTimeImmutable('01-08-2000'),
                new \DateTimeImmutable('15-07-2001'),
                Money::USD(1000_00),
            ],
            '2nd month of employment with base salary 1000,00 USD' => [
                new BaseSalary(Money::USD(1000_00)),
                new SenioritySupplement(Money::USD(100_00)),
                new \DateTimeImmutable('01-08-2000'),
                new \DateTimeImmutable('02-09-2000'),
                Money::USD(1000_00),
            ],
            'Salary before employment' => [
                new BaseSalary(Money::USD(1000_00)),
                new SenioritySupplement(Money::USD(100_00)),
                new \DateTimeImmutable('01-08-2000'),
                new \DateTimeImmutable('01-01-1999'),
                Money::USD(0),
            ],
        ];
    }
    /**
     * @return array<array{BaseSalary,PercentageSupplement,Money}>
     */
    public function providePercentageSupplementCases(): array
    {
        return [
            [new BaseSalary(Money::USD(1100_00)), new PercentageSupplement(0), Money::USD(1100_00)],
            [new BaseSalary(Money::USD(1100_00)), new PercentageSupplement(10), Money::USD(1210_00)],
            [new BaseSalary(Money::EUR(1100_00)), new PercentageSupplement(10), Money::EUR(1210_00)],
            [new BaseSalary(Money::USD(5000_00)), new PercentageSupplement(25), Money::USD(6250_00)],
            [new BaseSalary(Money::USD(1000_00)), new PercentageSupplement(100), Money::USD(2000_00)],
        ];
    }

    /** @dataProvider provideSenioritySupplementCases */
    public function testSenioritySupplement(
        BaseSalary $baseSalary,
        SenioritySupplement $senioritySupplement,
        \DateTimeImmutable $employedAt,
        \DateTimeImmutable $currentDate,
        Money $expectedTotalSalary,
    ): void
    {
        $department = new Department(Uuid::fromString('867f03c4-edb6-47cc-9ea7-2f4f43b62be1'), 'Customer Service', $senioritySupplement);

        $employee = new Employee(
            Uuid::fromString('e3bfc3d9-cf7c-405e-84a2-0a1a9bed6539'),
            new EmployeeName('Adam', 'Kowalski'),
            $employedAt,
            $department,
            $baseSalary,
        );

        $actualTotalSalary = $employee->totalSalaryAt($currentDate);

        self::assertEquals($expectedTotalSalary, $actualTotalSalary);
    }

    /** @dataProvider providePercentageSupplementCases */
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

}
