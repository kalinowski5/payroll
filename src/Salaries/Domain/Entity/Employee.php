<?php
declare(strict_types=1);


namespace XYZ\Salaries\Domain\Entity;

use Symfony\Component\Uid\Uuid;

final class Employee
{
    private string $id;
    private \DateTimeImmutable $employmentDate;
    private Department $department;
    private int $baseSalary; //@TODO: emmedable Money type

    public function __construct(
        Uuid $id,
        \DateTimeImmutable $employmentDate,
        Department $department,
        int $baseSalary //@TODO: Money
    ) {
        $this->id = (string) $id;
        $this->employmentDate = $employmentDate;
        $this->department = $department;
        $this->baseSalary = $baseSalary;
    }

    public function id(): Uuid
    {
        return Uuid::fromString($this->id);
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

    public function totalSalaryAt(\DateTimeImmutable $date): int //@TODO: Money
    {
        $percentageSupplement = $this->department->percentageSalarySupplement();

        if ($percentageSupplement) {
            return $this->baseSalary + (int) ($this->baseSalary * $percentageSupplement->percentage() / 100);
        }

        return $this->baseSalary;
    }
}
