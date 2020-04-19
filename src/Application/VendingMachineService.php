<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Coin;
use App\Domain\Count;
use App\Domain\ItemName;
use App\Domain\StockCoin;
use App\Domain\StockItem;
use App\Domain\VendingMachine;
use App\Domain\VendingMachineStorage;

class VendingMachineService
{
    private VendingMachineStorage $storage;

    public function __construct(VendingMachineStorage $storage)
    {
        $this->storage = $storage;
    }

    public function addItems(ItemName $name, Count $count): void
    {
        $vendingMachine = $this->vendingMachine();
        $vendingMachine->addItems($name, $count);
        $this->storage->save($vendingMachine);
    }

    public function stockItem(ItemName $name): StockItem
    {
        $vendingMachine = $this->vendingMachine();

        return $vendingMachine->stockItem($name);
    }

    /**
     * @return StockItem[]
     */
    public function stockItems(): array
    {
        return $this->vendingMachine()->stockItems();
    }

    public function addCoins(Coin $coin, Count $count): void
    {
        $vendingMachine = $this->vendingMachine();
        $vendingMachine->addCoins($coin, $count);
        $this->storage->save($vendingMachine);
    }

    public function stockCoin(Coin $coin): StockCoin
    {
        $vendingMachine = $this->vendingMachine();

        return $vendingMachine->stockCoin($coin);
    }

    /**
     * @return StockCoin[]
     */
    public function stockCoins(): array
    {
        return $this->vendingMachine()->stockCoins();
    }

    public function reset(): void
    {
        $vendingMachine = $this->vendingMachine();
        $vendingMachine->reset();
        $this->storage->save($vendingMachine);
    }

    private function vendingMachine(): VendingMachine
    {
        return $this->storage->load() ?? new VendingMachine();
    }
}
