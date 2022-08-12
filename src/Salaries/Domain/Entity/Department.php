<?php
declare(strict_types=1);


namespace XYZ\Salaries\Domain\Entity;


use XYZ\Salaries\Domain\ValueObject\PercentageSupplement;
use XYZ\Salaries\Domain\ValueObject\SenioritySupplement;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(/*repositoryClass: ProductRepository::class*/)]
final class Department
{
    #[ORM\Id]
    #[ORM\Column(type: "uuid", unique: true)]
    private Uuid $id;

    #[ORM\Column(type: "string")]
    private string $name; //@TODO: VO?

    private ?PercentageSupplement $percentageSalarySupplement = null;
    private ?SenioritySupplement $senioritySalarySupplement = null;

    public function __construct(Uuid $id, string $name, PercentageSupplement|SenioritySupplement $salarySupplement)
    {
        $this->id = $id;
        $this->name = $name;

        if ($salarySupplement instanceof PercentageSupplement) {
            $this->percentageSalarySupplement = $salarySupplement;
        }

        if ($salarySupplement instanceof SenioritySupplement) {
            $this->senioritySalarySupplement = $salarySupplement;
        }
    }

    public function id(): Uuid
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
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
