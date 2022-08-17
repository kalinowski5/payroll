<?php
declare(strict_types=1);

namespace XYZ\Tests\Salaries\Domain\ValueObject;

use Money\Money;
use PHPUnit\Framework\TestCase;
use XYZ\Salaries\Domain\ValueObject\EmployeeName;
use XYZ\Salaries\Domain\ValueObject\PayrollRow;
use XYZ\Salaries\Domain\ValueObject\Payslip;

class PayrollRowTest extends TestCase
{
    private PayrollRow $payrollRow;

    protected function setUp(): void
    {
        $this->payrollRow = new PayrollRow(
            new EmployeeName('Anne', 'Solider'),
            'Example department',
            new Payslip(Money::EUR(900), Money::EUR(500), '-'),
        );
        
        parent::setUp();
    }

    /**
     * @dataProvider provideMatchingStrings
     */
    public function testItCanBeMatchedByString(string $needle): void
    {
        self::assertTrue($this->payrollRow->matches($needle));
    }

    /**
     * @dataProvider provideNonMatchingStrings
     */
    public function testItCannotBeMatchedByWrongStrings(string $needle): void
    {
        self::assertFalse($this->payrollRow->matches($needle));
    }

    /**
     * @return array<int,string[]>
     */
    public function provideMatchingStrings(): array
    {
        return [
            ['Anne'],
            ['An'],
            ['ne'],
            ['anne'],
            ['AnnE'],
            ['Solider'],
            ['Solid'],
            ['Anne Solider'],
            ['Example department'],
            ['Example'],
            ['Example dep'],
        ];
    }

    /**
     * @return array<int,string[]>
     */
    public function provideNonMatchingStrings(): array
    {
        return [
            ['Supplement example'],
            ['John'],
            ['Doe'],
            ['Bla'],
            ['Google is better'],
            ['Elasticsearch is better'],
        ];
    }

}
