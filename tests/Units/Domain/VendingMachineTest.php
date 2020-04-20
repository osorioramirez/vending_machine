<?php

declare(strict_types=1);

namespace App\Tests\Units\Domain;

use App\Domain\Coin;
use App\Domain\Count;
use App\Domain\ExpendResultStatus;
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
    public function inserted_coins_must_be_added_to_cash_register(): void
    {
        $this->vendingMachine->insertCoin(Coin::FIVE_CENTS());

        $this->assertEquals(1, $this->vendingMachine->stockCoin(Coin::FIVE_CENTS())->count()->value());
    }

    /**
     * @test
     */
    public function it_should_expend_an_item(): void
    {
        $this->vendingMachine->addItems(ItemName::WATER(), new Count(1));
        $this->vendingMachine->addCoins(Coin::ONE_UNIT(), new Count(2));
        $this->vendingMachine->addCoins(Coin::TWENTY_FIVE_CENTS(), new Count(2));
        $this->vendingMachine->addCoins(Coin::TEN_CENTS(), new Count(2));
        $this->vendingMachine->addCoins(Coin::FIVE_CENTS(), new Count(2));

        $this->vendingMachine->insertCoin(Coin::ONE_UNIT());
        $result = $this->vendingMachine->expendItem(ItemName::WATER());

        $this->assertEquals(ExpendResultStatus::EXPENDED(), $result->status());
        $this->assertNotNull($result->change());
        $this->assertEquals(35, $result->change()->amount()->cents());
        $this->assertEquals([Coin::TWENTY_FIVE_CENTS(), Coin::TEN_CENTS()], $result->change()->coins());
        $this->assertEquals(0, $this->vendingMachine->amount()->cents());
        $this->assertEquals(3, $this->vendingMachine->stockCoin(Coin::ONE_UNIT())->count()->value());
        $this->assertEquals(1, $this->vendingMachine->stockCoin(Coin::TWENTY_FIVE_CENTS())->count()->value());
        $this->assertEquals(1, $this->vendingMachine->stockCoin(Coin::TEN_CENTS())->count()->value());
        $this->assertEquals(2, $this->vendingMachine->stockCoin(Coin::FIVE_CENTS())->count()->value());
        $this->assertEquals(0, $this->vendingMachine->stockItem(ItemName::WATER())->count()->value());
    }

    /**
     * @test
     */
    public function it_cannot_expend_an_item_when_it_is_not_available(): void
    {
        $this->vendingMachine->insertCoin(Coin::ONE_UNIT());
        $result = $this->vendingMachine->expendItem(ItemName::WATER());

        $this->assertEquals(ExpendResultStatus::NOT_AVAILABLE(), $result->status());
        $this->assertNull($result->change());
        $this->assertEquals(100, $this->vendingMachine->amount()->cents());
        $this->assertEquals(1, $this->vendingMachine->stockCoin(Coin::ONE_UNIT())->count()->value());
    }

    /**
     * @test
     */
    public function it_cannot_expend_an_item_when_amount_is_not_enough(): void
    {
        $this->vendingMachine->addItems(ItemName::WATER(), new Count(1));

        $this->vendingMachine->insertCoin(Coin::FIVE_CENTS());
        $result = $this->vendingMachine->expendItem(ItemName::WATER());

        $this->assertEquals(ExpendResultStatus::NOT_ENOUGH_AMOUNT(), $result->status());
        $this->assertNull($result->change());
        $this->assertEquals(5, $this->vendingMachine->amount()->cents());
        $this->assertEquals(1, $this->vendingMachine->stockCoin(Coin::FIVE_CENTS())->count()->value());
        $this->assertEquals(1, $this->vendingMachine->stockItem(ItemName::WATER())->count()->value());
    }

    /**
     * @test
     */
    public function it_cannot_expend_an_item_when_there_is_no_change(): void
    {
        $this->vendingMachine->addItems(ItemName::WATER(), new Count(1));

        $this->vendingMachine->insertCoin(Coin::ONE_UNIT());
        $result = $this->vendingMachine->expendItem(ItemName::WATER());

        $this->assertEquals(ExpendResultStatus::NOT_CHANGE(), $result->status());
        $this->assertNull($result->change());
        $this->assertEquals(100, $this->vendingMachine->amount()->cents());
        $this->assertEquals(1, $this->vendingMachine->stockCoin(Coin::ONE_UNIT())->count()->value());
        $this->assertEquals(1, $this->vendingMachine->stockItem(ItemName::WATER())->count()->value());
    }

    /**
     * @test
     */
    public function it_should_return_insert_coins(): void
    {
        $this->vendingMachine->insertCoin(Coin::TWENTY_FIVE_CENTS());
        $this->vendingMachine->insertCoin(Coin::TWENTY_FIVE_CENTS());

        $change = $this->vendingMachine->returnCoins();

        $this->assertEquals(50, $change->amount()->cents());
        $this->assertEquals([Coin::TWENTY_FIVE_CENTS(), Coin::TWENTY_FIVE_CENTS()], $change->coins());
        $this->assertEquals(0, $this->vendingMachine->amount()->cents());
        $this->assertEquals(0, $this->vendingMachine->stockCoin(Coin::TWENTY_FIVE_CENTS())->count()->value());
    }

    /**
     * @test
     */
    public function it_should_return_a_change_equals_to_insert_coins(): void
    {
        $this->vendingMachine->addCoins(Coin::ONE_UNIT(), new Count(1));

        $this->vendingMachine->insertCoin(Coin::TWENTY_FIVE_CENTS());
        $this->vendingMachine->insertCoin(Coin::TWENTY_FIVE_CENTS());
        $this->vendingMachine->insertCoin(Coin::TWENTY_FIVE_CENTS());
        $this->vendingMachine->insertCoin(Coin::TWENTY_FIVE_CENTS());

        $change = $this->vendingMachine->returnCoins();

        $this->assertEquals(100, $change->amount()->cents());
        $this->assertEquals([Coin::ONE_UNIT()], $change->coins());
        $this->assertEquals(0, $this->vendingMachine->amount()->cents());
        $this->assertEquals(4, $this->vendingMachine->stockCoin(Coin::TWENTY_FIVE_CENTS())->count()->value());
        $this->assertEquals(0, $this->vendingMachine->stockCoin(Coin::ONE_UNIT())->count()->value());
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
