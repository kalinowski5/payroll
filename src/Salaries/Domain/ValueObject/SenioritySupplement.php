<?php
declare(strict_types=1);


namespace XYZ\Salaries\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Money\Currency;
use Money\Money;

final class SenioritySupplement
{
    public const MAX_NUMBER_OF_YEARS = 10; //After this number of years, the salary is not increased anymore

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
        return new Money(
            $this->amount,
            //@phpstan-ignore-next-line - Currency is always non-empty-string
            new Currency($this->currency),
        );
    }

    public function name(): string
    {
        return 'Seniority';
    }
}
