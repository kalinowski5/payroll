<?php
declare(strict_types=1);


namespace XYZ\Salaries\UI\CLI;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Uid\Uuid;
use XYZ\Salaries\Domain\Entity\Department;
use XYZ\Salaries\Domain\ValueObject\PercentageSupplement;

#[AsCommand(name: 'xyz:payroll:seed-example-data')]
final class SeedExampleDataCommand extends Command //@TODO: Test me!
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $departments = [
            new Department(Uuid::v4(), 'Human Resources', new PercentageSupplement(15)),
            new Department(Uuid::v4(), 'Customer Service', new PercentageSupplement(10)),
            new Department(Uuid::v4(), 'Marketing', new PercentageSupplement(10)),
            new Department(Uuid::v4(), 'IT', new PercentageSupplement(5)),
        ];
        foreach ($departments as $department) {
            $this->entityManager->persist($department);
        }
        $this->entityManager->flush();

        $output->writeln('Example data was added to database!');

        return Command::SUCCESS;
    }
}
