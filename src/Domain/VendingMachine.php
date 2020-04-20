<?php

declare(strict_types=1);

namespace App\Domain;

use App\Core\Domain\AggregateRoot;
use Webmozart\Assert\Assert;

class VendingMachine implements AggregateRoot
{
    private Inventory $inventory;
    private CashRegister $cashRegister;
    private Money $amount;

    public function __construct()
    {
        $this->inventory = new Inventory();
        $this->cashRegister = new CashRegister();
        $this->amount = Money::zero();
    }

    public function insertCoin(Coin $coin): void
    {
        $this->amount = $this->amount->add($coin->toMoney());
        $this->cashRegister->addCoin($coin);
    }

    public function amount(): Money
    {
        return $this->amount;
    }

    public function expendItem(ItemName $name): ExpendResult
    {
        $itemStock = $this->stockItem($name);
        if ($itemStock->count()->value() === 0) {
            return ExpendResult::notAvailable($name);
        }

        if ($itemStock->price()->isGreaterThan($this->amount())) {
            return ExpendResult::notEnoughAmount($name);
        }

        $change = $this->cashRegister->change($this->amount()->subtract($itemStock->price()));
        if ($change === null) {
            return ExpendResult::notChange($name);
        }

        $this->inventory->expendItem($name);
        $this->amount = Money::zero();

        return ExpendResult::expended($name, $change);
    }

    public function returnCoins(): Change
    {
        $change = $this->cashRegister->change($this->amount());
        if ($change !== null) {
            $this->amount = Money::zero();

            return $change;
        }

        // it should never happen
        throw new \LogicException('Sorry, cannot perform this action in that moment');
    }

    public function reset(): void
    {
        Assert::true($this->amount()->isZero(), 'The machine cannot be reset. Extract the coins first');

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
