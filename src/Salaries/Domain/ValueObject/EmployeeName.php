<?php
declare(strict_types=1);


namespace XYZ\Salaries\Domain\ValueObject;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
final class EmployeeName
{
    #[ORM\Column(type: "string")]
    private string $firstName;

    #[ORM\Column(type: "string")]
    private string $lastName;

    public function __construct(string $firstName, string $lastName)
    {
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function firstName(): string
    {
        return $this->firstName;
    }

    public function lastName(): string
    {
        return $this->lastName;
    }

    public function __toString(): string
    {
        return sprintf("%s %s", $this->firstName, $this->lastName);
    }
}
