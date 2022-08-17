<?php
declare(strict_types=1);


namespace XYZ\Salaries\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Money\Currency;
use Money\Money;

final class SenioritySupplement
{
    #[ORM\Column(type: "integer", nullable: true, options: ["comment" => 'Amount in cents'])]
    private int $amount;

    #[ORM\Column(type: "string", length: 3, nullable: true)]
    private string $currency;

    public function __construct(Money $valuePerYearOfEmployment)
    {
        $this->amount = (int) $valuePerYearOfEmployment->getAmount();
        $this->currency = $valuePerYearOfEmployment->getCurrency()->getCode();
    }

    public function valuePerYearOfEmployment(): Money
    {
        return new Money($this->amount, new Currency($this->currency));
    }
}
