<?php

namespace TextWheel\Test\Cli;

use PHPUnit_Framework_TestCase;
use TextWheel\Cli\ProcessCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class ProcessCommandTest extends PHPUnit_Framework_TestCase
{
    public function testExecute()
    {
        $application = new Application();
        $application->add(new ProcessCommand());

        $command = $application->find('process');
        $commandTester = new CommandTester($command);
        $commandTester->execute(array(
            'command' => $command->getName(),
            'rules' => __DIR__ . '/../vendor/textwheel/engine/tests/fixtures/subwheel.yaml',
            'text' => 'This is a simple test written by myself',
        ));

        $this->assertSame('This is a simpli tist writtin by mysilf'.PHP_EOL, $commandTester->getDisplay());
    }
}
