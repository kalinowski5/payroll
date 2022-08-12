<?php
declare(strict_types=1);


namespace XYZ\Salaries\Domain\ValueObject;


final class PercentageSupplement
{
    public function __construct(private readonly int $percentage) //@TODO: only accept positive numbers
    {
    }

    public function percentage(): int
    {
        return $this->percentage;
    }
}
