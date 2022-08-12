<?php
declare(strict_types=1);


namespace XYZ\Salaries\UI\CLI;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'xyz:payroll:generate')]
final class GeneratePayrollCommand extends Command //@TODO: Test me!
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('Payroll is being generated...');

        return Command::SUCCESS;
    }
}
