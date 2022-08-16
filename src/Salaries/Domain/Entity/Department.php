<?php
declare(strict_types=1);


namespace XYZ\Salaries\Domain\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use XYZ\Salaries\Domain\ValueObject\PercentageSupplement;
use XYZ\Salaries\Domain\ValueObject\SenioritySupplement;
use Symfony\Component\Uid\Uuid;

final class Department
{
    private string $id;
    private string $name; //@TODO: VO?
    private Collection $employees;
    private ?PercentageSupplement $percentageSalarySupplement = null;
    private ?SenioritySupplement $senioritySalarySupplement = null;

    public function __construct(Uuid $id, string $name, PercentageSupplement|SenioritySupplement $salarySupplement)
    {
        $this->id = (string) $id;
        $this->name = $name;
        $this->employees = new ArrayCollection();

        if ($salarySupplement instanceof PercentageSupplement) {
            $this->percentageSalarySupplement = $salarySupplement;
        }

        if ($salarySupplement instanceof SenioritySupplement) {
            $this->senioritySalarySupplement = $salarySupplement;
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
     * @return Collection|Employee[]
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
        return $this->percentageSalarySupplement;
    }

    public function senioritySalarySupplement(): ?SenioritySupplement
    {
        return $this->senioritySalarySupplement;
    }

}
