<?php

namespace App\Tests\Behat\Contexts;

use App\Application\VendingMachineService;
use App\Domain\Count;
use App\Domain\ItemName;
use App\Domain\VendingMachineStorage;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;

class VendingMachineContext implements Context
{
    private VendingMachineService $vendingMachineService;
    private VendingMachineStorage $storage;

    public function __construct(VendingMachineService $vendingMachineService, VendingMachineStorage $storage)
    {
        $this->vendingMachineService = $vendingMachineService;
        $this->storage = $storage;
    }

    /**
     * @BeforeScenario
     */
    public function deleteVendingMachine()
    {
        $this->storage->delete();
    }

    /**
     * @Given I provision the vending machine with the following items:
     */
    public function iProvisionTheVendingMachineWithTheFollowingItems(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            $this->vendingMachineService->addItems(new ItemName($row['name']), new Count($row['count']));
        }
    }

    /**
     * @Then the :itemName stock must be equal to :count
     */
    public function theStockMustBeEqualTo($itemName, $count)
    {
        Assert::assertEquals(
            $count,
            $this->vendingMachineService->stockItem(new ItemName($itemName))->count()->value()
        );
    }
}
