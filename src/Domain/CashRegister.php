<?php

declare(strict_types=1);

namespace App\Domain;

class CashRegister
{
    /** @var int[] */
    private array $coins;

    public function __construct()
    {
        $this->coins = [];
        foreach (Coin::values() as $coin) {
            $this->coins[$coin->getValue()] = 0;
        }
    }
}
