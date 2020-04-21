<?php

declare(strict_types=1);

namespace App\Domain;

class Money
{
    private int $cents;

    public static function fromFloat(float $vale): self
    {
        return new self((int) round($vale * 100, 0));
    }

    public static function zero(): self
    {
        return new self(0);
    }

    public function __construct(int $cents)
    {
        $this->cents = $cents;
    }

    public function add(Money $money): self
    {
        return new self($this->cents() + $money->cents());
    }

    public function subtract(Money $money): self
    {
        return new self($this->cents() - $money->cents());
    }

    public function multiplyBy(float $multiplier): self
    {
        return new self((int) round($this->cents() * $multiplier, 0));
    }

    public function cents(): int
    {
        return $this->cents;
    }

    public function toFloat(): float
    {
        return $this->cents() / 100.0;
    }

    public function equals(Money $other): bool
    {
        return $this->compareTo($other) === 0;
    }

    public function isGreaterThan(Money $other): bool
    {
        return $this->compareTo($other) === 1;
    }

    public function isGreaterThanOrEqualTo(Money $other): bool
    {
        return $this->compareTo($other) >= 0;
    }

    public function isLessThan(Money $other): bool
    {
        return $this->compareTo($other) === -1;
    }

    public function isLessThanOrEqualTo(Money $other): bool
    {
        return $this->compareTo($other) <= 0;
    }

    public function compareTo(Money $other): int
    {
        return $this->cents() <=> $other->cents();
    }

    public function isZero(): bool
    {
        return $this->equals(self::zero());
    }

    public function isNegative(): bool
    {
        return $this->isLessThan(self::zero());
    }

    public function __toString()
    {
        return number_format($this->toFloat(), 2, '.', '');
    }
}
