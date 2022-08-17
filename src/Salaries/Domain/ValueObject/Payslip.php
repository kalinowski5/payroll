<?php
declare(strict_types=1);

namespace XYZ\Salaries\Domain\ValueObject;

use Money\Currency;
use Money\Money;

final class Payslip
{
    private const EMPTY_SUPPLEMENT_INFO = '-';

    public readonly Money $totalSalary;

    public function __construct(
        public readonly Money $baseSalary,
        public readonly Money $supplementAmount,
        public readonly string $supplementInfo,
    ) {
        $this->totalSalary = Money::sum($this->baseSalary, $this->supplementAmount);
    }

    public static function empty(Currency $currency): self
    {
        return new self(
            new Money(0, $currency),
            new Money(0, $currency),
            self::EMPTY_SUPPLEMENT_INFO,
        );
    }
}
