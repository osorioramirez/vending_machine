<?php

declare(strict_types=1);

namespace App\Domain;

class ExpendResult
{
    private ExpendResultStatus $status;
    /** @var ItemName */
    private ItemName $name;
    private ?Change $change;

    public static function expended(ItemName $name, Change $change): self
    {
        return new self(ExpendResultStatus::EXPENDED(), $name, $change);
    }

    public static function notChange(ItemName $name): self
    {
        return new self(ExpendResultStatus::NOT_CHANGE(), $name);
    }

    public static function notAvailable(ItemName $name): self
    {
        return new self(ExpendResultStatus::NOT_AVAILABLE(), $name);
    }

    public static function notEnoughAmount(ItemName $name): self
    {
        return new self(ExpendResultStatus::NOT_ENOUGH_AMOUNT(), $name);
    }

    public function __construct(ExpendResultStatus $status, ItemName $name, ?Change $change = null)
    {
        $this->status = $status;
        $this->name = $name;
        $this->change = $change;
    }

    public function status(): ExpendResultStatus
    {
        return $this->status;
    }

    public function name(): ItemName
    {
        return $this->name;
    }

    public function change(): ?Change
    {
        return $this->change;
    }
}
