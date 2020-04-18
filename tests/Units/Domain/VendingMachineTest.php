<?php

declare(strict_types=1);

namespace App\Tests\Units\Domain;

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
    public function it_should_be_empty_after_reset(): void
    {
        $this->vendingMachine->addItems(ItemName::WATER(), new Count(10));
        $this->vendingMachine->addItems(ItemName::SODA(), new Count(20));

        $this->vendingMachine->reset();

        $this->assertEquals(0, $this->vendingMachine->stockItem(ItemName::WATER())->count()->value());
        $this->assertEquals(0, $this->vendingMachine->stockItem(ItemName::SODA())->count()->value());
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
}
