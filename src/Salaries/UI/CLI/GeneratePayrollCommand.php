<?php
declare(strict_types=1);


namespace XYZ\Salaries\UI\CLI;

use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use XYZ\Salaries\Domain\Entity\Employee;
use XYZ\Salaries\Domain\Repository\EmployeeRepository;

#[AsCommand(name: 'xyz:payroll:generate')]
final class GeneratePayrollCommand extends Command
{
    private const MONEY_FORMAT = 'pl_PL';

    private EmployeeRepository $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $employees = $this->employeeRepository->findAll();

        $now = new \DateTimeImmutable(); //@TODO: inject clock

        $table = new Table($output);
        $table
            ->setHeaders([
                'First name',
                'Last name',
                'Department',
                'Base salary',
                'Salary supplement',
                'Salary supplement type',
                'Total salary'
            ])
            ->setRows(
                array_map(function (Employee $employee) use ($now): array {
                    $moneyFormatter = self::moneyFormatter();
                    $employeeName = $employee->name();
                    $salarySummary = $employee->payslipAt($now);

                    return [
                        $employeeName->firstName(),
                        $employeeName->lastName(),
                        $employee->department()->name(),
                        $moneyFormatter->format($salarySummary->baseSalary),
                        $moneyFormatter->format($salarySummary->supplementAmount),
                        $salarySummary->supplementInfo,
                        $moneyFormatter->format($salarySummary->totalSalary),
                    ];
                }, $employees),
            )
        ;
        $table->render();

        return Command::SUCCESS;
    }

    private static function moneyFormatter(): IntlMoneyFormatter
    {
        $currencies = new ISOCurrencies();
        $numberFormatter = new \NumberFormatter(self::MONEY_FORMAT, \NumberFormatter::CURRENCY);

        return new IntlMoneyFormatter($numberFormatter, $currencies);
    }
}
