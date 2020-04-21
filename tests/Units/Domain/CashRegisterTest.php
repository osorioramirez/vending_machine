<?php

declare(strict_types=1);

namespace App\Tests\Units\Domain;

use App\Domain\CashRegister;
use App\Domain\Coin;
use App\Domain\Count;
use App\Domain\Money;
use App\Tests\Units\TestCase;

class CashRegisterTest extends TestCase
{
    protected CashRegister $cashRegister;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cashRegister = new CashRegister();
    }

    /**
     * @test
     */
    public function there_should_be_no_change_when_it_is_empty(): void
    {
        $this->assertNull($this->cashRegister->change(new Money(100)));
    }

    /**
     * @test
     */
    public function coins_should_not_be_withdrawn_when_there_is_no_change(): void
    {
        $this->cashRegister->addCoins(Coin::TWENTY_FIVE_CENTS(), new Count(3));

        $change = $this->cashRegister->change(new Money(65));

        $this->assertNull($change);
        $this->assertEquals(3, $this->cashRegister->stockCoin(Coin::TWENTY_FIVE_CENTS())->count()->value());
        $this->assertEquals(new Money(75), $this->cashRegister->total());
    }

    /**
     * @test
     */
    public function coins_should_be_withdrawn_when_there_is_change(): void
    {
        $this->cashRegister->addCoins(Coin::TWENTY_FIVE_CENTS(), new Count(3));

        $change = $this->cashRegister->change(new Money(75));

        $this->assertNotNull($change);
        $this->assertEquals(0, $this->cashRegister->stockCoin(Coin::TWENTY_FIVE_CENTS())->count()->value());
        $this->assertEquals(Money::zero(), $this->cashRegister->total());
        $this->assertEquals(new Money(75), $change->amount());
    }

    /**
     * @test
     */
    public function there_should_be_no_change_when_amount_is_greater_than_total(): void
    {
        $this->cashRegister->addCoins(Coin::ONE_UNIT(), new Count(2));
        $this->cashRegister->addCoins(Coin::TWENTY_FIVE_CENTS(), new Count(4));
        $this->cashRegister->addCoins(Coin::TEN_CENTS(), new Count(10));
        $this->cashRegister->addCoins(Coin::FIVE_CENTS(), new Count(20));

        $change = $this->cashRegister->change(new Money(600));

        $this->assertNull($change);
    }

    /**
     * @test
     */
    public function there_should_be_change_when_amount_is_equal_to_total(): void
    {
        $this->cashRegister->addCoins(Coin::ONE_UNIT(), new Count(2));
        $this->cashRegister->addCoins(Coin::TWENTY_FIVE_CENTS(), new Count(4));
        $this->cashRegister->addCoins(Coin::TEN_CENTS(), new Count(10));
        $this->cashRegister->addCoins(Coin::FIVE_CENTS(), new Count(20));

        $change = $this->cashRegister->change(new Money(500));

        $this->assertNotNull($change);
        $this->assertEquals(new Money(500), $change->amount());
    }
}
