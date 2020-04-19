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

    private function ensureStockCoin(Coin $coin): StockCoin
    {
        if (!isset($this->stock[(string) $coin])) {
            $this->stock[(string) $coin] = new StockCoin($coin);
        }

        return $this->stock[(string) $coin];
    }
}
