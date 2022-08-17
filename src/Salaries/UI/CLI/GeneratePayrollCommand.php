<?php
declare(strict_types=1);

namespace XYZ\Salaries\UI\CLI;

use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use XYZ\Salaries\Domain\Enum\SortingField;
use XYZ\Salaries\Domain\Service\PayrollProvider;
use XYZ\Salaries\Domain\ValueObject\PayrollRow;

#[AsCommand(name: 'xyz:payroll:generate')]
final class GeneratePayrollCommand extends Command
{
    private const MONEY_FORMAT = 'pl_PL';

    private const FILTER_OPTION_NAME = 'filter';

    private const PAYROLL_TABLE_HEADERS = [
        'First name',
        'Last name',
        'Department',
        'Base salary',
        'Salary supplement',
        'Salary supplement type',
        'Total salary',
    ];

    private PayrollProvider $payrollProvider;

    public function __construct(PayrollProvider $payrollProvider)
    {
        $this->payrollProvider = $payrollProvider;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption(
            self::FILTER_OPTION_NAME,
            null,
            InputArgument::OPTIONAL,
            'Filter employees by first name, last name or department',
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $sortingField = $this->askForSortingField($input, $output);
        $filterBy = $input->getOption(self::FILTER_OPTION_NAME);

        $payroll = $this->payrollProvider->generatePayroll($sortingField, $filterBy);

        $tableRows = array_map($this->mapPayrollIntoTableRows(), $payroll);

        $table = new Table($output);
        $table->setHeaders(self::PAYROLL_TABLE_HEADERS);
        $table->setRows($tableRows);
        $table->render();

        return Command::SUCCESS;
    }

    private function mapPayrollIntoTableRows(): \Closure
    {
        return fn(PayrollRow $payrollRow): array => [
            $payrollRow->employeeName->firstName(),
            $payrollRow->employeeName->lastName(),
            $payrollRow->departmentName,
            self::moneyFormatter()->format($payrollRow->payslip->baseSalary),
            self::moneyFormatter()->format($payrollRow->payslip->supplementAmount),
            $payrollRow->payslip->supplementInfo,
            self::moneyFormatter()->format($payrollRow->payslip->totalSalary),
        ];
    }

    private static function moneyFormatter(): IntlMoneyFormatter
    {
        $currencies = new ISOCurrencies();
        $numberFormatter = new \NumberFormatter(self::MONEY_FORMAT, \NumberFormatter::CURRENCY);

        return new IntlMoneyFormatter($numberFormatter, $currencies);
    }

    private function askForSortingField(InputInterface $input, OutputInterface $output): SortingField
    {
        $helper = $this->getHelper('question');

        $sortingFieldQuestion = new ChoiceQuestion(
            'How to sort the payroll?',
            array_column(SortingField::cases(), 'value'),
            0
        );

        return SortingField::from(
            $helper->ask($input, $output, $sortingFieldQuestion)
        );
    }
}
