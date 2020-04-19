<?php

declare(strict_types=1);

namespace App\Tests\Units\Domain;

use App\Domain\Money;
use App\Tests\Units\TestCase;

class MoneyTest extends TestCase
{
    /**
     * @test
     */
    public function it_has_an_amount_after_created(): void
    {
        $money = new Money(10);

        $this->assertEquals(10, $money->cents());
    }

    /**
     * @test
     */
    public function it_can_be_created_from_a_float(): void
    {
        $money = Money::fromFloat(5.35);

        $this->assertEquals(535, $money->cents());
    }

    /**
     * @test
     */
    public function it_can_be_converted_to_float(): void
    {
        $money = new Money(535);

        $this->assertEquals(5.35, $money->toFloat());
    }

    /**
     * @test
     */
    public function it_can_be_converted_to_string(): void
    {
        $money = new Money(10);

        $this->assertEquals('0.10', (string) $money);
    }

    /**
     * @test
     */
    public function adding_a_money_returns_the_sum_of_both(): void
    {
        $money = new Money(10);
        $result = $money->add(new Money(5));

        $this->assertEquals(15, $result->cents());
    }

    /**
     * @test
     */
    public function it_should_be_immutable_after_an_addition(): void
    {
        $money = new Money(10);
        $money->add(new Money(5));

        $this->assertEquals(10, $money->cents());
    }
}