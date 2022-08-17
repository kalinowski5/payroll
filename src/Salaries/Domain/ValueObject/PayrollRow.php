<?php
declare(strict_types=1);

namespace XYZ\Salaries\Domain\ValueObject;

final class PayrollRow
{
    public function __construct(
        public readonly EmployeeName $employeeName,
        public readonly string $departmentName,
        public readonly Payslip $payslip,
    )
    {
    }

    public function matches(string $filter): bool
    {
        $employeeNameMatch = str_contains(
            strtolower((string)$this->employeeName),
            strtolower($filter)
        );

        $departmentNameMatch = str_contains(
            strtolower($this->departmentName),
            strtolower($filter)
        );

        return $employeeNameMatch || $departmentNameMatch;
    }
}
