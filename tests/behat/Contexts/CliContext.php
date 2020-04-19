<?php

namespace App\Tests\Behat\Contexts;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;

class CliContext implements Context
{
    private KernelInterface $kernel;
    private Application $application;
    private CommandTester $commandTester;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->application = new Application($kernel);
    }

    public function execute(string $commandName, array $input = [], array $options = [])
    {
        $command = $this->application->find($commandName);
        $this->commandTester = new CommandTester($command);
        $this->commandTester->execute($input, $options);
    }

    /**
     * @Given I am the system
     */
    public function iAmTheSystem()
    {
        Assert::assertSame('cli', php_sapi_name());
    }

    /**
     * @When I execute the cli command :commandName
     */
    public function iExecuteTheCliCommand(string $commandName)
    {
        $this->execute($commandName);
    }

    /**
     * @Then the display should be equals to:
     */
    public function theDisplayShouldBeEqualsTo(PyStringNode $display)
    {
        Assert::assertEquals((string) $display, $this->commandTester->getDisplay());
    }

    /**
     * @When I execute the cli command :commandName with arguments:
     */
    public function iExecuteTheCliCommandWithArguments(string $commandName, TableNode $table)
    {
        $input = $table->getRowsHash();
        unset($input['argument']);

        $this->execute($commandName, $input);
    }

    /**
     * @Given the exit status code should be equal to :statusCode
     */
    public function theExitStatusCodeShouldBeEqualTo(int $statusCode)
    {
        Assert::assertEquals($statusCode, $this->commandTester->getStatusCode());
    }

    /**
     * @Given print last display
     */
    public function printLastDisplay()
    {
        echo $this->commandTester->getDisplay();
    }
}
