<?php
declare(strict_types=1);


namespace XYZ\Salaries\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;
use Money\Money;
use Symfony\Component\Uid\Uuid;
use XYZ\Salaries\Domain\ValueObject\BaseSalary;
use XYZ\Salaries\Domain\ValueObject\EmployeeName;

#[ORM\Entity]
class Employee
{
    #[ORM\Id]
    #[ORM\Column(type: "string", unique: true)]
    private string $id;

    #[ORM\Embedded(class: EmployeeName::class, columnPrefix: false)]
    private EmployeeName $name;

    #[ORM\Column(type: "date_immutable")]
    private \DateTimeImmutable $employmentDate;

    #[ORM\ManyToOne(targetEntity: Department::class, inversedBy: 'employees')]
    private Department $department;

    #[ORM\Embedded(class: BaseSalary::class)]
    private BaseSalary $baseSalary;

    public function __construct(
        Uuid $id,
        EmployeeName $name,
        \DateTimeImmutable $employmentDate,
        Department $department,
        BaseSalary $baseSalary
    ) {
        $this->id = (string) $id;
        $this->name = $name;
        $this->employmentDate = $employmentDate;
        $this->department = $department;
        $this->baseSalary = $baseSalary; //@TODO: assert the currency match department's currency
    }

    public function id(): Uuid
    {
        return Uuid::fromString($this->id);
    }

    public function employmentDate(): \DateTimeImmutable
    {
        return $this->employmentDate;
    }

    public function name(): EmployeeName
    {
        return $this->name;
    }

    public function department(): Department
    {
        return $this->department;
    }

    public function baseSalary(): BaseSalary
    {
        return $this->baseSalary;
    }

    public function totalSalaryAt(\DateTimeImmutable $date): Money
    {
        $percentageSupplement = $this->department->percentageSalarySupplement();

        if ($percentageSupplement) {
            $baseSalaryAmount = $this->baseSalary->value();
            $supplementAmount = $baseSalaryAmount
                ->multiply($percentageSupplement->value())
                ->divide(100);

            return Money::sum($baseSalaryAmount, $supplementAmount);
        }

        return $this->baseSalary->value();
    }
}
