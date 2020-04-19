<?php

declare(strict_types=1);

namespace App\Domain;

use App\Core\Domain\AggregateRoot;

class VendingMachine implements AggregateRoot
{
    private Inventory $inventory;
    private CashRegister $cashRegister;

    public function __construct()
    {
        $this->inventory = new Inventory();
        $this->cashRegister = new CashRegister();
    }

    public function reset(): void
    {
        $this->inventory->reset();
        $this->cashRegister->reset();
    }

    public function addItems(ItemName $name, Count $count): void
    {
        $this->inventory->addItems($name, $count);
    }

    public function stockItem(ItemName $name): StockItem
    {
        return $this->inventory->stockItem($name);
    }

    /**
     * @return StockItem[]
     */
    public function stockItems(): array
    {
        return $this->inventory->stockItems();
    }

    public function addCoins(Coin $coin, Count $count): void
    {
        $this->cashRegister->addCoins($coin, $count);
    }

    public function stockCoin(Coin $coin): StockCoin
    {
        return $this->cashRegister->stockCoin($coin);
    }

    /**
     * @return StockCoin[]
     */
    public function stockCoins(): array
    {
        return $this->cashRegister->stockCoins();
    }
}
