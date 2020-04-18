<?php

declare(strict_types=1);

namespace App\Domain;

use App\Core\Domain\AggregateRoot;

class VendingMachine implements AggregateRoot
{
    private Inventory $inventory;

    public function __construct()
    {
        $this->inventory = new Inventory();
    }

    public function reset(): void
    {
        $this->inventory->reset();
    }

    public function addItems(ItemName $name, Count $count): void
    {
        $this->inventory->addItems($name, $count);
    }

    public function stockItem(ItemName $name): StockItem
    {
        return $this->inventory->stockItem($name);
    }
}
