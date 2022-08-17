<?php
declare(strict_types=1);


namespace XYZ\Salaries\Domain\ValueObject;


final class PercentageSupplement
{
    public function __construct(
        private readonly int $value,
    )
    {
        if ($this->value < 0) {
            throw new \InvalidArgumentException('Value of Percentage Supplement must be greater than zero');
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function name(): string
    {
        return 'Percentage';
    }
}
