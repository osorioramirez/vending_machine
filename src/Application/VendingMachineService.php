<?php

declare(strict_types=1);

namespace App\Application;

use App\Domain\Count;
use App\Domain\ItemName;
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
        $vendingMachine = $this->loadOrCreate();
        $vendingMachine->addItems($name, $count);
        $this->storage->save($vendingMachine);
    }

    public function stockItem(ItemName $name): StockItem
    {
        $vendingMachine = $this->loadOrCreate();

        return $vendingMachine->stockItem($name);
    }

    public function reset(): void
    {
        $vendingMachine = $this->loadOrCreate();
        $vendingMachine->reset();
        $this->storage->save($vendingMachine);
    }

    private function loadOrCreate(): VendingMachine
    {
        return $this->storage->load() ?? new VendingMachine();
    }
}
