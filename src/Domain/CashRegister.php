<?php

declare(strict_types=1);

namespace App\Domain;

class CashRegister
{
    /** @var StockCoin[] */
    private array $stock;

    public function __construct()
    {
        $this->reset();
    }

    public function reset(): void
    {
        $this->stock = [];
    }

    public function addCoin(Coin $coin): void
    {
        $this->addCoins($coin, new Count(1));
    }

    public function addCoins(Coin $coin, Count $count): void
    {
        $stockCoin = $this->ensureStockCoin($coin);
        $stockCoin->setCount($stockCoin->count()->add($count));
    }

    public function stockCoin(Coin $coin): StockCoin
    {
        return StockCoin::from($this->ensureStockCoin($coin));
    }

    /**
     * @return StockCoin[]
     */
    public function stockCoins(): array
    {
        return array_map(
            fn (Coin $coin): StockCoin => $this->stockCoin($coin),
            Coin::values()
        );
    }

    public function change(Money $amount): ?Change
    {
        if ($amount->isZero()) {
            return Change::zero();
        }

        $change = $this->bruteForceChange($amount, $this->coinsArray(), 0, Change::zero());
        if ($change !== null) {
            $this->withdrawChange($change);
        }

        return $change;
    }

    private function bruteForceChange(Money $amount, array $coins, int $index, Change $change): ?Change
    {
        if ($amount->isZero()) {
            return $change;
        }

        if ($amount->isNegative() || $index >= count($coins)) {
            return null;
        }

        /** @var Coin $coin */
        $coin = $coins[$index];
        $changeFound = $this->bruteForceChange(
            $amount->subtract($coin->toMoney()),
            $coins,
            $index + 1,
            $change->addCoin($coin)
        );

        return $changeFound ?? $this->bruteForceChange($amount, $coins, $index + 1, $change);
    }

    private function coinsArray(): array
    {
        $stock = array_values($this->stock);
        usort($stock, fn (StockCoin $a, StockCoin $b): int => $b->coin()->getValue() <=> $a->coin()->getValue());

        return array_reduce(
            array_map(
                fn (StockCoin $stockCoin): array => array_fill(0, $stockCoin->count()->value(), $stockCoin->coin()),
                $stock
            ),
            fn (array $carry, array $coins): array => array_merge($carry, $coins),
            []
        );
    }

    private function ensureStockCoin(Coin $coin): StockCoin
    {
        if (!isset($this->stock[(string) $coin])) {
            $this->stock[(string) $coin] = new StockCoin($coin);
        }

        return $this->stock[(string) $coin];
    }

    private function withdrawChange(Change $change): void
    {
        foreach ($change->coins() as $coin) {
            $stockCoin = $this->ensureStockCoin($coin);
            $stockCoin->setCount($stockCoin->count()->dec());
        }
    }
}
