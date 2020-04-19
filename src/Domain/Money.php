<?php

declare(strict_types=1);

namespace App\Domain;

class Money
{
    private int $cents;

    public static function fromFloat(float $vale): self
    {
        return new Money((int) round($vale * 100, 0));
    }

    public function __construct(int $cents)
    {
        $this->cents = $cents;
    }

    public function cents(): int
    {
        return $this->cents;
    }

    public function toFloat(): float
    {
        return $this->cents() / 100.0;
    }

    public function __toString()
    {
        return number_format($this->toFloat(), 2, '.', '');
    }
}
