<?php
declare(strict_types=1);


namespace XYZ\Salaries\Domain\ValueObject;


final class PercentageSupplement
{
    public function __construct(
        private readonly int $value,
    ) //@TODO: only accept positive numbers (incl. tests)
    {
    }

    public function value(): int
    {
        return $this->value;
    }
}
