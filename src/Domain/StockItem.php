<?php

declare(strict_types=1);

namespace App\Domain;

class StockItem
{
    private ItemName $name;
    private Money $price;
    private Count $count;

    public static function from(StockItem $stockItem): self
    {
        return new self($stockItem->name(), $stockItem->price(), $stockItem->count());
    }

    public function __construct(ItemName $name, Money $price, ?Count $count = null)
    {
        $this->name = $name;
        $this->count = $count ?? new Count(0);
        $this->price = $price;
    }

    public function name(): ItemName
    {
        return $this->name;
    }

    public function price(): Money
    {
        return $this->price;
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
