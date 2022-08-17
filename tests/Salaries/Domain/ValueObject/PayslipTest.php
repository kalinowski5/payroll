<?php
declare(strict_types=1);

namespace XYZ\Tests\Salaries\Domain\ValueObject;

use Money\Currency;
use Money\Money;
use PHPUnit\Framework\TestCase;
use XYZ\Salaries\Domain\ValueObject\Payslip;

class PayslipTest extends TestCase
{
    public function testTotalSalaryIsCalculated(): void
    {
        $payslip = new Payslip(
            Money::EUR(3000_00),
            Money::EUR(500_00),
            'Dummy supplement',
        );

        self::assertEquals(Money::EUR(3500_00), $payslip->totalSalary);
    }

    public function testEmptyPayslipCanBeCreated(): void
    {
        $payslip = Payslip::empty(new Currency('EUR'));

        self::assertEquals(new Payslip(Money::EUR(0), Money::EUR(0),'-'), $payslip);
    }

    public function testIncompatibleCurrencies(): void
    {
        self::expectExceptionObject(new \InvalidArgumentException('Currencies must be identical'));

        new Payslip(
            Money::EUR(3000_00),
            Money::AUD(500_00),
            'Dummy supplement',
        );
    }
}
