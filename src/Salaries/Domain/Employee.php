<?php
declare(strict_types=1);


namespace XYZ\Salaries\Domain;

use XYZ\Salaries\Domain\Entity\Department;

final class Employee
{

    private string $id;

    private \DateTimeImmutable $employmentDate;

    private Department $department;

    private int $baseSalary; //@TODO: emmedable Money type

    public function __construct(
        string $id, //@TODO: uuid
        \DateTimeImmutable $employmentDate,
        Department $department,
        int $baseSalary //@TODO: Money
    ) {
        $this->id = $id;
        $this->employmentDate = $employmentDate;
        $this->department = $department;
        $this->baseSalary = $baseSalary;
    }

    public function id(): string
    {
        return $this->id;
    }

    public function employmentDate(): \DateTimeImmutable
    {
        return $this->employmentDate;
    }

    public function department(): Department
    {
        return $this->department;
    }


    public function baseSalary(): int //@TODO: Money
    {
        return $this->baseSalary;
    }

    public function totalSalary(): int //@TODO: Money
    {
        return $this->baseSalary;
    }
}
