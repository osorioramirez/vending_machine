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

        $this->assertEquals(10, $money->amount());
    }
}
