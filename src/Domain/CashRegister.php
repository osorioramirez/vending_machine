<?php

declare(strict_types=1);

namespace App\Domain;

class CashRegister
{
    /** @var StockCoin[] */
    private array $stock;
    private Money $total;

    public function __construct()
    {
        $this->reset();
    }

    public function total(): Money
    {
        return $this->total;
    }

    public function reset(): void
    {
        $this->stock = [];
        $this->total = Money::zero();
    }

    public function addCoin(Coin $coin): void
    {
        $this->addCoins($coin, new Count(1));
    }

    public function addCoins(Coin $coin, Count $count): void
    {
        $stockCoin = $this->ensureStockCoin($coin);
        $stockCoin->setCount($stockCoin->count()->add($count));
        $this->total = $this->total->add($coin->toMoney()->multiplyBy($count->value()));
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

        if ($amount->isGreaterThan($this->total())) {
            return null;
        }

        $change = $this->bruteForceChange($amount, $this->sortedStock(), 0, Change::zero());
        if ($change !== null) {
            $this->withdrawChange($change);
        }

        return $change;
    }

    private function bruteForceChange(Money $amount, array $stock, int $index, Change $change): ?Change
    {
        if ($amount->isZero()) {
            return $change;
        }

        if ($amount->isNegative() || $index >= count($stock)) {
            return null;
        }

        /** @var StockCoin $stockCoin */
        $stockCoin = $stock[$index];
        $changeFound = null;
        $coinsCount = min(intdiv($amount->cents(), $stockCoin->coin()->cents()), $stockCoin->count()->value());
        while ($coinsCount > 0 && $changeFound === null) {
            $changeFound = $this->bruteForceChange(
                $amount->subtract($stockCoin->coin()->toMoney()->multiplyBy($coinsCount)),
                $stock,
                $index + 1,
                $change->addCoins($stockCoin->coin(), new Count($coinsCount))
            );

            --$coinsCount;
        }

        return $changeFound ?? $this->bruteForceChange($amount, $stock, $index + 1, $change);
    }

    private function sortedStock(): array
    {
        $stock = array_values($this->stock);
        usort($stock, fn (StockCoin $a, StockCoin $b): int => $b->coin()->cents() <=> $a->coin()->cents());

        return $stock;
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
            $this->total = $this->total->subtract($coin->toMoney());
        }
    }
}
