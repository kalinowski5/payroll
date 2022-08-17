<?php
declare(strict_types=1);

namespace XYZ\Tests\Salaries\Domain\Service;

use ContainerG4yH6zA\getDebug_ArgumentResolver_RequestAttribute_InnerService;
use Money\Money;
use PHPUnit\Framework\TestCase;
use XYZ\Salaries\Domain\Entity\Department;
use XYZ\Salaries\Domain\Entity\Employee;
use XYZ\Salaries\Domain\Enum\SortingField;
use XYZ\Salaries\Domain\Repository\EmployeeRepository;
use XYZ\Salaries\Domain\Service\PayrollProvider;
use XYZ\Salaries\Domain\ValueObject\EmployeeName;
use XYZ\Salaries\Domain\ValueObject\PayrollRow;
use XYZ\Salaries\Domain\ValueObject\Payslip;

class PayrollProviderTest extends TestCase
{
    private PayrollProvider $systemUnderTest;

    public function testPayrollCanBeGenerated(): void
    {
        $expectedRows = [
            new PayrollRow(
                new EmployeeName('Anne', 'Solider'),
                'Example department',
                new Payslip(Money::EUR(900), Money::EUR(500), '-'),
            ),
            new PayrollRow(
                new EmployeeName('John', 'Doe'),
                'Example department',
                new Payslip(Money::EUR(1000), Money::EUR(0), 'Dummy supplement'),
            ),
            new PayrollRow(
                new EmployeeName('Kailey', 'Ainslee'),
                'Acme department',
                new Payslip(Money::EUR(1200), Money::EUR(0), '-'),
            ),
        ];

        self::assertEquals(
            $expectedRows,
            $this->systemUnderTest->generatePayroll(SortingField::BASE_SALARY)
        );
    }

    /**
     * @dataProvider provideSortingTestCases
     *
     * @param array<int,array{SortingField,string[]}> $expectedOrder
     */
    public function testPayrollCanBeSorted(SortingField $sortingField, array $expectedOrder): void
    {
        self::assertEquals(
            $expectedOrder,
            array_map(function (PayrollRow $payrollRow): string {
                return (string) $payrollRow->employeeName;
            }, $this->systemUnderTest->generatePayroll($sortingField)
        ));
    }

    /**
     * @return array<int,array{SortingField,string[]}>
     */
    public function provideSortingTestCases(): array
    {
        return [
            [SortingField::FIRST_NAME, ['Anne Solider', 'John Doe', 'Kailey Ainslee']],
            [SortingField::LAST_NAME, ['Kailey Ainslee', 'John Doe', 'Anne Solider']],
            [SortingField::BASE_SALARY, ['Anne Solider', 'John Doe', 'Kailey Ainslee']],
            [SortingField::TOTAL_SALARY, ['John Doe', 'Kailey Ainslee', 'Anne Solider']],
            [SortingField::DEPARTMENT, ['Kailey Ainslee', 'John Doe', 'Anne Solider']],
            [SortingField::SALARY_SUPPLEMENT_TYPE, ['Anne Solider', 'Kailey Ainslee', 'John Doe']],
            [SortingField::SALARY_SUPPLEMENT_AMOUNT, ['John Doe', 'Kailey Ainslee', 'Anne Solider']],
        ];
    }


    protected function setUp(): void
    {
        $employeeRepository = $this->createMock(EmployeeRepository::class);

        $dummyDepartment = $this->createMock(Department::class);
        $dummyDepartment->method('name')->willReturn('Example department');

        $acmeDepartment = $this->createMock(Department::class);
        $acmeDepartment->method('name')->willReturn('Acme department');

        $employee1 = $this->createMock(Employee::class);
        $employee1->method('payslipAt')->willReturn(new Payslip(Money::EUR(1000), Money::EUR(0), 'Dummy supplement'));
        $employee1->method('department')->willReturn($dummyDepartment);
        $employee1->method('name')->willReturn(new EmployeeName('John', 'Doe'));

        $employee2 = $this->createMock(Employee::class);
        $employee2->method('payslipAt')->willReturn(new Payslip(Money::EUR(900), Money::EUR(500), '-'));
        $employee2->method('department')->willReturn($dummyDepartment);
        $employee2->method('name')->willReturn(new EmployeeName('Anne', 'Solider'));

        $employee3 = $this->createMock(Employee::class);
        $employee3->method('payslipAt')->willReturn(new Payslip(Money::EUR(1200), Money::EUR(0), '-'));
        $employee3->method('department')->willReturn($acmeDepartment);
        $employee3->method('name')->willReturn(new EmployeeName('Kailey', 'Ainslee'));

        $employeeRepository->method('findAll')
            ->willReturn([
                $employee1,
                $employee2,
                $employee3,
            ]);

        $this->systemUnderTest = new PayrollProvider($employeeRepository);

        parent::setUp();
    }

}
