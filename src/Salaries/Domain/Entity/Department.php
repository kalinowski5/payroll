<?php
declare(strict_types=1);


namespace XYZ\Salaries\Domain\Entity;


use XYZ\Salaries\Domain\ValueObject\PercentageSupplement;
use XYZ\Salaries\Domain\ValueObject\SenioritySupplement;

final class Department
{
    private string $name; //@TODO: VO?

    private ?PercentageSupplement $percentageSalarySupplement = null;
    private ?SenioritySupplement $senioritySalarySupplement = null;

    public function __construct(string $name, PercentageSupplement|SenioritySupplement $salarySupplement)
    {
        $this->name = $name;

        if ($salarySupplement instanceof PercentageSupplement) {
            $this->percentageSalarySupplement = $salarySupplement;
        }

        if ($salarySupplement instanceof SenioritySupplement) {
            $this->senioritySalarySupplement = $salarySupplement;
        }
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
