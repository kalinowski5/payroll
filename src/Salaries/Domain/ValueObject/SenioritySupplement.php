<?php
declare(strict_types=1);


namespace XYZ\Salaries\Domain\ValueObject;


final class SenioritySupplement
{

    public function __construct(private readonly int $amountPerYearOfEmployment) //@TODO: Money
    {
    }


    public function amountPerYearOfEmployment(): int
    {
        return $this->amountPerYearOfEmployment;
    }


}
