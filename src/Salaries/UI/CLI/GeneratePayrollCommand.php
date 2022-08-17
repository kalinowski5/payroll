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

        $moneyFormatter = self::moneyFormatter();

        $now = new \DateTimeImmutable(); //@TODO: inject clock

        $table = new Table($output);
        $table
            ->setHeaders(['First name', 'Last name', 'Department', 'Base salary', 'Total salary']) //@TODO: More columns
            ->setRows(
                array_map(fn (Employee $employee) => [
                    $employee->name()->firstName(),
                    $employee->name()->lastName(),
                    $employee->department()->name(),
                    $moneyFormatter->format($employee->baseSalary()->value()),
                    $moneyFormatter->format($employee->totalSalaryAt($now)),
                ], $employees),
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
