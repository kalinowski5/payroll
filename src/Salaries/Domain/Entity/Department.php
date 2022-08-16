<?php
declare(strict_types=1);


namespace XYZ\Salaries\Domain\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use XYZ\Salaries\Domain\ValueObject\PercentageSupplement;
use XYZ\Salaries\Domain\ValueObject\SenioritySupplement;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
final class Department
{
    #[ORM\Id]
    #[ORM\Column(type: "string", unique: true)]
    private string $id;

    #[ORM\Column(type: "string")]
    private string $name; //@TODO: VO?

    #[ORM\OneToMany(mappedBy: 'department', targetEntity: Employee::class)]
    private Collection $empolyees;

    private ?PercentageSupplement $percentageSalarySupplement = null;
    private ?SenioritySupplement $senioritySalarySupplement = null;

    public function __construct(Uuid $id, string $name, PercentageSupplement|SenioritySupplement $salarySupplement)
    {
        $this->id = (string) $id;
        $this->name = $name;
        $this->empolyees = new ArrayCollection();

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
        return $this->empolyees;
    }

    public function hire(Employee $employee): void
    {
        $this->empolyees->add($employee);
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
