<?php

declare(strict_types=1);

namespace App\Tests\Units\Domain;

use App\Domain\Coin;
use App\Domain\Count;
use App\Domain\ItemName;
use App\Domain\VendingMachine;
use App\Tests\Units\TestCase;

class VendingMachineTest extends TestCase
{
    protected VendingMachine $vendingMachine;

    protected function setUp(): void
    {
        parent::setUp();

        $this->vendingMachine = new VendingMachine();
    }

    /**
     * @test
     */
    public function inserted_coins_must_be_added_to_the_total_amount(): void
    {
        $this->vendingMachine->insertCoin(Coin::FIVE_CENTS());
        $this->vendingMachine->insertCoin(Coin::TWENTY_FIVE_CENTS());

        $this->assertEquals(30, $this->vendingMachine->amount()->cents());
    }

    /**
     * @test
     */
    public function it_should_be_empty_after_reset(): void
    {
        $this->vendingMachine->addItems(ItemName::WATER(), new Count(10));
        $this->vendingMachine->addCoins(Coin::FIVE_CENTS(), new Count(10));

        $this->vendingMachine->reset();

        $this->assertEquals(0, $this->vendingMachine->stockItem(ItemName::WATER())->count()->value());
        $this->assertEquals(0, $this->vendingMachine->stockCoin(Coin::FIVE_CENTS())->count()->value());
    }

    /**
     * @test
     */
    public function it_cannot_be_reset_with_coins(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The machine cannot be reset. Extract the coins first');
        $this->vendingMachine->insertCoin(Coin::FIVE_CENTS());

        $this->vendingMachine->reset();
    }

    /**
     * @test
     */
    public function it_stock_increases_after_adding_items(): void
    {
        $this->vendingMachine->addItems(ItemName::WATER(), new Count(10));
        $this->vendingMachine->addItems(ItemName::WATER(), new Count(10));

        $this->assertEquals(20, $this->vendingMachine->stockItem(ItemName::WATER())->count()->value());
    }

    /**
     * @test
     */
    public function the_stock_cannot_be_modified_outside_the_aggregate(): void
    {
        $this->vendingMachine->addItems(ItemName::WATER(), new Count(10));
        $itemStock = $this->vendingMachine->stockItem(ItemName::WATER());

        $itemStock->setCount(new Count(30));

        $this->assertEquals(10, $this->vendingMachine->stockItem(ItemName::WATER())->count()->value());
    }

    /**
     * @test
     */
    public function it_cash_increases_after_adding_cash(): void
    {
        $this->vendingMachine->addCoins(Coin::FIVE_CENTS(), new Count(10));
        $this->vendingMachine->addCoins(Coin::FIVE_CENTS(), new Count(10));

        $this->assertEquals(20, $this->vendingMachine->stockCoin(Coin::FIVE_CENTS())->count()->value());
    }

    /**
     * @test
     */
    public function the_cash_cannot_be_modified_outside_the_aggregate(): void
    {
        $this->vendingMachine->addCoins(Coin::FIVE_CENTS(), new Count(10));
        $stockCoin = $this->vendingMachine->stockCoin(Coin::FIVE_CENTS());

        $stockCoin->setCount(new Count(30));

        $this->assertEquals(10, $this->vendingMachine->stockCoin(Coin::FIVE_CENTS())->count()->value());
    }
}
