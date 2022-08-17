<?php
declare(strict_types=1);

namespace XYZ\Salaries\Domain\Repository;

use XYZ\Salaries\Domain\Entity\Employee;

interface EmployeeRepository
{
    /**
     * @return Employee[]
     */
    public function findAll(): array;
}
