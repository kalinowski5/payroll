<?php
declare(strict_types=1);


namespace XYZ\Salaries\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
final class Employee
{
    #[ORM\Id]
    #[ORM\Column(type: "string", unique: true)]
    private string $id;

    private \DateTimeImmutable $employmentDate;

    #[ORM\ManyToOne(targetEntity: Department::class, inversedBy: 'employees')]
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
