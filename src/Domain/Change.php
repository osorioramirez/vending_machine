<?php

declare(strict_types=1);

namespace App\Domain;

class Change
{
    private Money $amount;
    /** @var Coin[] */
    private array $coins;

    public static function zero(): self
    {
        return new self(Money::zero(), []);
    }

    public function __construct(Money $amount, array $coins)
    {
        $this->amount = $amount;
        $this->coins = $coins;
    }

    public function addCoins(Coin $coin, Count $count): self
    {
        return new self(
            $this->amount()->add($coin->toMoney()->multiplyBy($count->value())),
            array_merge($this->coins, array_fill(0, $count->value(), $coin)));
    }

    public function amount(): Money
    {
        return $this->amount;
    }

    /**
     * @return Coin[]
     */
    public function coins(): array
    {
        return $this->coins;
    }
}
