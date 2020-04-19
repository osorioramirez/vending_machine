<?php

declare(strict_types=1);

namespace App\Domain;

class StockCoin
{
    private Coin $coin;
    private Count $count;

    public static function from(StockCoin $stockCoin): self
    {
        return new self($stockCoin->coin(), $stockCoin->count());
    }

    public function __construct(Coin $coin, ?Count $count = null)
    {
        $this->coin = $coin;
        $this->count = $count ?? new Count(0);
    }

    public function coin(): Coin
    {
        return $this->coin;
    }

    public function count(): Count
    {
        return $this->count;
    }

    public function setCount(Count $count)
    {
        $this->count = $count;
    }
}
