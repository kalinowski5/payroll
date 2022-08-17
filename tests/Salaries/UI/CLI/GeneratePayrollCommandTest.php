<?php
declare(strict_types=1);

namespace XYZ\Tests\Salaries\UI\CLI;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Persistence\ObjectManager;
use Money\Money;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\Uid\Uuid;
use XYZ\Salaries\Domain\Entity\Department;
use XYZ\Salaries\Domain\Entity\Employee;
use XYZ\Salaries\Domain\ValueObject\BaseSalary;
use XYZ\Salaries\Domain\ValueObject\EmployeeName;
use XYZ\Salaries\Domain\ValueObject\PercentageSupplement;
use XYZ\Salaries\Domain\ValueObject\SenioritySupplement;

class GeneratePayrollCommandTest extends KernelTestCase
{
    private Application $application;
    private ObjectManager $objectManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();
        $this->application = new Application($kernel);

        /** @var Registry $registry */
        $registry =  $kernel->getContainer()->get('doctrine');
        $this->objectManager = $registry->getManager();

        $this->seedFixtures();

        parent::setUp();
    }

    public function testPayrollCanBeGenerated(): void
    {
        $command = $this->application->find('xyz:payroll:generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            // pass arguments to the helper
//            'username' => 'Wouter',

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        $commandTester->assertCommandIsSuccessful();

        $bufferedOutput = new BufferedOutput();
        $table = new Table($bufferedOutput);
        $table
            ->setHeaders(['First name', 'Last name', 'Department', 'Base salary', 'Total salary'])
            ->setRows([
                ['Adam', 'Kowalski', 'Human Resources ', '1 000,00 USD', '2 000,00 USD'],
                ['Anna', 'Nowak', 'Customer Service', '2 000,00 USD', '2 400,00 USD'],
            ])
            ->render()
        ;

        $expectedOutput = $bufferedOutput->fetch();
        $actualOutput = $commandTester->getDisplay();

        self::assertEquals($expectedOutput, $actualOutput);
    }

    private function seedFixtures(): void
    {
        $humanResourcesDepartment = new Department(
            Uuid::fromString('2c2b52ed-d8c6-4326-934b-ff11521c3ce5'),
            'Human Resources',
            new SenioritySupplement(Money::USD(100_00)),
        );

        $customerServiceDepartment = new Department(
            Uuid::fromString('29e62eb2-4971-468e-9862-25ab3c9b8532'),
            'Customer Service',
            new PercentageSupplement(20),
        );

        $employees = [
            new Employee(
                Uuid::fromString('d0a74e86-12c6-456c-aaa2-754e4d9f0dce'),
                new EmployeeName('Adam', 'Kowalski'),
                new \DateTimeImmutable('2000-01-01'),
                $humanResourcesDepartment,
                new BaseSalary(Money::USD(1000_00)),
            ),
            new Employee(
                Uuid::fromString('4dd4e998-ad07-4a6b-bd0f-5ebb1137acea'),
                new EmployeeName('Anna', 'Nowak'),
                new \DateTimeImmutable('2020-01-01'),
                $customerServiceDepartment,
                new BaseSalary(Money::USD(2000_00)),
            ),
        ];

        $this->objectManager->persist($customerServiceDepartment);
        $this->objectManager->persist($humanResourcesDepartment);

        foreach ($employees as $employee) {
            $this->objectManager->persist($employee);
        }

        $this->objectManager->flush();
    }
}
