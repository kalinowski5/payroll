<?php
declare(strict_types=1);

namespace XYZ\Salaries\Domain\Service;

use XYZ\Salaries\Domain\Entity\Employee;
use XYZ\Salaries\Domain\Enum\SortingField;
use XYZ\Salaries\Domain\Repository\EmployeeRepository;
use XYZ\Salaries\Domain\ValueObject\PayrollRow;
use XYZ\SharedKernel\Domain\Clock;

final class PayrollProvider
{
    public function __construct(
        private readonly EmployeeRepository $employeeRepository,
        private readonly Clock $clock,
    )
    {
    }

    /**
     * @return array<PayrollRow>
     */
    public function generatePayroll(SortingField $sortBy, ?string $filterBy = null): array
    {
        $now = $this->clock->now();

        $employees = $this->employeeRepository->findAll();

        $payroll = array_map(
            fn(Employee $employee): PayrollRow =>
            new PayrollRow($employee->name(), $employee->department()->name(), $employee->payslipAt($now)),
            $employees
        );

        if ($filterBy) {
            $payroll = array_filter($payroll, fn(PayrollRow $payrollRow) => $payrollRow->matches($filterBy));
        }

        usort($payroll, $this->sortRows($sortBy));

        return $payroll;
    }

    private function sortRows(SortingField $sortBy): \Closure
    {
        return fn(PayrollRow $a, PayrollRow $b): int => match ($sortBy) {
            SortingField::FIRST_NAME => $a->employeeName->firstName() <=> $b->employeeName->firstName(),
            SortingField::LAST_NAME => $a->employeeName->lastName() <=> $b->employeeName->lastName(),
            SortingField::BASE_SALARY => $a->payslip->baseSalary <=> $b->payslip->baseSalary,
            SortingField::TOTAL_SALARY => $a->payslip->totalSalary <=> $b->payslip->totalSalary,
            SortingField::DEPARTMENT => $a->departmentName <=> $b->departmentName,
            SortingField::SALARY_SUPPLEMENT_AMOUNT => $a->payslip->supplementAmount <=> $b->payslip->supplementAmount,
            SortingField::SALARY_SUPPLEMENT_TYPE => $a->payslip->supplementInfo <=> $b->payslip->supplementInfo,
        };
    }

}
