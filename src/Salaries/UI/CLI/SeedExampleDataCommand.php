<?php
declare(strict_types=1);


namespace XYZ\Salaries\UI\CLI;

use Doctrine\ORM\EntityManagerInterface;
use Money\Money;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;
use XYZ\Salaries\Domain\Entity\Department;
use XYZ\Salaries\Domain\Entity\Employee;
use XYZ\Salaries\Domain\ValueObject\BaseSalary;
use XYZ\Salaries\Domain\ValueObject\EmployeeName;
use XYZ\Salaries\Domain\ValueObject\PercentageSupplement;
use XYZ\Salaries\Domain\ValueObject\SenioritySupplement;

#[AsCommand(name: 'xyz:payroll:seed-example-data')]
final class SeedExampleDataCommand extends Command
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        //@TODO: It can be extracted to a separate service
        $departments = [
            new Department(Uuid::v4(), 'Human Resources', new PercentageSupplement(15)),
            new Department(Uuid::v4(), 'Customer Service', new PercentageSupplement(10)),
            new Department(Uuid::v4(), 'Marketing', new SenioritySupplement(Money::USD(500_00))),
            new Department(Uuid::v4(), 'IT', new PercentageSupplement(5)),
            new Department(Uuid::v4(), 'Research and Development', new SenioritySupplement(Money::USD(300_00))),
        ];
        foreach ($departments as $department) {
            $this->entityManager->persist($department);
        }

        $this->entityManager->flush();

        $employees = [
            new Employee(Uuid::v4(), new EmployeeName('Zeshan', 'Hooper'), new \DateTimeImmutable('1 year ago'), $departments[0], new BaseSalary(Money::USD(1900_00))),
            new Employee(Uuid::v4(), new EmployeeName('Uzma', 'Adamson'), new \DateTimeImmutable('2 years ago'), $departments[0], new BaseSalary(Money::USD(4000_00))),
            new Employee(Uuid::v4(), new EmployeeName('Abbi', 'Doe'), new \DateTimeImmutable('10 years ago'), $departments[0], new BaseSalary(Money::USD(2300_00))),
            new Employee(Uuid::v4(), new EmployeeName('Javan', 'Chang'), new \DateTimeImmutable('7 years ago'), $departments[2], new BaseSalary(Money::USD(5000_00))),
            new Employee(Uuid::v4(), new EmployeeName('Jamil', 'Cox'), new \DateTimeImmutable('2 years ago'), $departments[2], new BaseSalary(Money::USD(2900_00))),
            new Employee(Uuid::v4(), new EmployeeName('Pascal', 'Oliver'), new \DateTimeImmutable('2 months ago'), $departments[3], new BaseSalary(Money::USD(3000_00))),
            new Employee(Uuid::v4(), new EmployeeName('Leslie', 'Doe'), new \DateTimeImmutable('1 month ago'), $departments[2], new BaseSalary(Money::USD(1000_00))),
            new Employee(Uuid::v4(), new EmployeeName('Rayhan', 'Busby'), new \DateTimeImmutable('3 months ago'), $departments[4], new BaseSalary(Money::USD(1900_00))),
            new Employee(Uuid::v4(), new EmployeeName('Carlos', 'Doe'), new \DateTimeImmutable('4 years ago'), $departments[1], new BaseSalary(Money::USD(1999_00))),
            new Employee(Uuid::v4(), new EmployeeName('Romany', 'Kaur'), new \DateTimeImmutable('13 years ago'), $departments[1], new BaseSalary(Money::USD(2300_00))),
            new Employee(Uuid::v4(), new EmployeeName('John', 'Fellows'), new \DateTimeImmutable('2 years ago'), $departments[4], new BaseSalary(Money::USD(1000_00))),
            new Employee(Uuid::v4(), new EmployeeName('Renesmae', 'Rich'), new \DateTimeImmutable('5 years ago'), $departments[3], new BaseSalary(Money::USD(1650_00))),
            new Employee(Uuid::v4(), new EmployeeName('Veronica', 'Doe'), new \DateTimeImmutable('15 months ago'), $departments[3], new BaseSalary(Money::USD(2150_00))),
            new Employee(Uuid::v4(), new EmployeeName('Carlos', 'Long'), new \DateTimeImmutable('15 months ago'), $departments[1], new BaseSalary(Money::USD(2190_00))),
            new Employee(Uuid::v4(), new EmployeeName('Alan', 'Coates'), new \DateTimeImmutable('20 months ago'), $departments[4], new BaseSalary(Money::USD(3100_00))),
            new Employee(Uuid::v4(), new EmployeeName('Montell', 'Mcbride'), new \DateTimeImmutable('3 months ago'), $departments[0], new BaseSalary(Money::USD(1990_00))),
        ];

        foreach ($employees as $employee) {
            $this->entityManager->persist($employee);
        }

        $this->entityManager->flush();

        $output->writeln('Example data was added to database!');

        return Command::SUCCESS;
    }
}
