<?php
declare(strict_types=1);


namespace XYZ\Salaries\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;
use Money\Currency;
use Money\Money;

#[ORM\Embeddable]
final class BaseSalary
{
    #[ORM\Column(type: "integer", options: ["comment" => 'Amount in cents'])]
    private int $amount;

    #[ORM\Column(type: "string", length: 3)]
    private string $currency;

    public function __construct(Money $value)
    {
        $this->amount = (int) $value->getAmount();
        $this->currency = $value->getCurrency()->getCode();
    }

    public function value(): Money
    {
        return new Money(
            $this->amount,
            //@phpstan-ignore-next-line - Currency is always non-empty-string
            new Currency($this->currency),
        );
    }
}
