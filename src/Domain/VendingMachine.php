<?php

declare(strict_types=1);

namespace App\Domain;

use App\Core\Domain\AggregateRoot;
use Webmozart\Assert\Assert;

class VendingMachine implements AggregateRoot
{
    private Inventory $inventory;
    private CashRegister $cashRegister;
    /** @var Coin[] */
    private array $coins;
    private Money $amount;

    public function __construct()
    {
        $this->inventory = new Inventory();
        $this->cashRegister = new CashRegister();
        $this->coins = [];
        $this->amount = new Money(0);
    }

    public function insertCoin(Coin $coin): void
    {
        $this->coins[] = $coin;
        $this->amount = $this->amount->add($coin->toMoney());
    }

    public function amount(): Money
    {
        return $this->amount;
    }

    public function reset(): void
    {
        Assert::isEmpty($this->coins, 'The machine cannot be reset. Extract the coins first');

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
