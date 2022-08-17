<?php
declare(strict_types=1);

namespace XYZ\Tests\Salaries\UI\CLI;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Tester\CommandTester;

class GeneratePayrollCommandTest extends KernelTestCase
{
    public function testPayrollCanBeGenerated(): void
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('xyz:payroll:generate');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            // pass arguments to the helper
//            'username' => 'Wouter',

            // prefix the key with two dashes when passing options,
            // e.g: '--some-option' => 'option_value',
        ]);

        $commandTester->assertCommandIsSuccessful();

        $actualOutput = $commandTester->getDisplay();

        $bufferedOutput = new BufferedOutput();
        $table = new Table($bufferedOutput);
        $table
            ->setHeaders(['First name', 'Last name', 'Department', 'Base salary', 'Total salary'])
            ->setRows([])
            ->render()
        ;

        $expectedOutput = $bufferedOutput->fetch();

        self::assertEquals($expectedOutput, $actualOutput);
    }
}
