<?php
declare(strict_types=1);


namespace XYZ\Salaries\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class PercentageSupplement
{
    public function __construct(
        #[ORM\Column(type: "integer", nullable: true)]
        private readonly int $value
    ) //@TODO: only accept positive numbers (incl. tests)
    {
    }

    public function value(): int
    {
        return $this->value;
    }
}
