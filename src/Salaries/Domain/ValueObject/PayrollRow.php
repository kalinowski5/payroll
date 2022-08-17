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
}
