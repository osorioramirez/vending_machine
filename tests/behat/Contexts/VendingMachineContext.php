<?php

namespace App\Tests\Behat\Contexts;

use App\Application\VendingMachineService;
use App\Domain\Coin;
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
     * @Given I provision the vending machine with the following coins:
     */
    public function iProvisionTheVendingMachineWithTheFollowingCoins(TableNode $table)
    {
        foreach ($table->getHash() as $row) {
            Coin::assertIsValidStringValue($row['coin']);
            $this->vendingMachineService->addCoins(
                Coin::fromString($row['coin']),
                new Count($row['count'])
            );
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

    /**
     * @Given the :coin coin stock must be equal to :count
     */
    public function theCoinStockMustBeEqualTo($coin, $count)
    {
        Assert::assertEquals(
            $count,
            $this->vendingMachineService->stockCoin(Coin::fromString($coin))->count()->value()
        );
    }

    /**
     * @Given I insert a :coin coin
     */
    public function iInsertACoin($coin)
    {
        $this->vendingMachineService->insertCoin(Coin::fromString($coin));
    }

    /**
     * @Then the amount should be equal to :amount
     */
    public function theAmountShouldBeEqualTo($amount)
    {
        Assert::assertEquals((float) $amount, $this->vendingMachineService->amount()->toFloat());
    }
}
