<?php
declare(strict_types=1);


namespace XYZ\Salaries\Domain\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Money\Currency;
use Money\Money;
use XYZ\Salaries\Domain\ValueObject\PercentageSupplement;
use XYZ\Salaries\Domain\ValueObject\SenioritySupplement;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class Department
{
    #[ORM\Id]
    #[ORM\Column(type: "string", unique: true)]
    private string $id;

    #[ORM\Column(type: "string")]
    private string $name;

    /**
     * @var Collection<int,Employee>
     */
    #[ORM\OneToMany(mappedBy: 'department', targetEntity: Employee::class)]
    private Collection $employees;

    #[ORM\Column(type: "integer", nullable: true)]
    private ?int $percentageSalarySupplement = null;

    #[ORM\Column(type: "integer", nullable: true, options: ["comment" => 'Amount in cents'])]
    private ?int $senioritySalarySupplementAmount = null;

    #[ORM\Column(type: "string", length: 3, nullable: true)]
    private ?string $senioritySalarySupplementCurrency = null;

    public function __construct(Uuid $id, string $name, PercentageSupplement|SenioritySupplement $salarySupplement)
    {
        $this->id = (string) $id;
        $this->name = $name;
        $this->employees = new ArrayCollection();

        //PercentageSupplement and SenioritySupplement aren't embeddable because of Doctrine's limitations: https://stackoverflow.com/a/45262491/2432403

        if ($salarySupplement instanceof PercentageSupplement) {
            $this->percentageSalarySupplement = $salarySupplement->value();
        }

        if ($salarySupplement instanceof SenioritySupplement) {
            $this->senioritySalarySupplementAmount = (int) $salarySupplement->valuePerYearOfEmployment()->getAmount();
            $this->senioritySalarySupplementCurrency = (string) $salarySupplement->valuePerYearOfEmployment()->getCurrency();
        }
    }

    public function id(): Uuid
    {
        return Uuid::fromString($this->id);
    }

    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return Collection<int,Employee>
     */
    public function employees(): Collection
    {
        return $this->employees;
    }

    public function hire(Employee $employee): void
    {
        $this->employees->add($employee);
    }

    public function percentageSalarySupplement(): ?PercentageSupplement
    {
        if (!is_null($this->percentageSalarySupplement)) {
            return new PercentageSupplement($this->percentageSalarySupplement);
        }

        return null;
    }

    public function senioritySalarySupplement(): ?SenioritySupplement
    {
        if (!is_null($this->senioritySalarySupplementAmount) && !is_null($this->senioritySalarySupplementCurrency) && $this->senioritySalarySupplementCurrency !== '') {
            return new SenioritySupplement(
                new Money($this->senioritySalarySupplementAmount, new Currency($this->senioritySalarySupplementCurrency))
            );
        }

        return null;
    }

}
