<?php

declare(strict_types=1);

namespace App\Domain;

class Inventory
{
    private const WATER_VALUE = 65;
    private const JUICE_VALUE = 100;
    private const SODA_VALUE = 150;

    /** @var StockItem[] */
    private array $stock;

    public function __construct()
    {
        $this->reset();
    }

    public function reset(): void
    {
        $this->stock = [];
    }

    public function addItems(ItemName $name, Count $count): void
    {
        $stockItem = $this->ensureStockItem($name);
        $stockItem->setCount($stockItem->count()->add($count));
    }

    public function expendItem(ItemName $name): void
    {
        $stockItem = $this->ensureStockItem($name);
        $stockItem->setCount($stockItem->count()->dec());
    }

    public function stockItem(ItemName $name): StockItem
    {
        return StockItem::from($this->ensureStockItem($name));
    }

    /**
     * @return StockItem[]
     */
    public function stockItems(): array
    {
        return array_map(
            fn (ItemName $name): StockItem => $this->stockItem($name),
            ItemName::values()
        );
    }

    private function ensureStockItem(ItemName $name): StockItem
    {
        if (!isset($this->stock[(string) $name])) {
            $this->stock[(string) $name] = new StockItem($name, $this->itemPrice($name));
        }

        return $this->stock[(string) $name];
    }

    private function itemPrice(ItemName $name): Money
    {
        switch ((string) $name) {
            case (string) ItemName::WATER():
                return new Money(self::WATER_VALUE);
            case (string) ItemName::JUICE():
                return new Money(self::JUICE_VALUE);
            case (string) ItemName::SODA():
                return new Money(self::SODA_VALUE);
            default:
                throw new \InvalidArgumentException(sprintf('There is no known price for %s', $name));
        }
    }
}
