<?php

declare(strict_types=1);

namespace App\Domain;

use Webmozart\Assert\Assert;

class Count
{
    private int $value;

    public function __construct(int $value)
    {
        Assert::greaterThanEq($value, 0, 'The count must be greater than or equal to 0. Got: %s');

        $this->value = $value;
    }

    public function add(Count $count): Count
    {
        return new Count($this->value() + $count->value());
    }

    public function dec(): Count
    {
        Assert::greaterThan($this->value(), 0, 'Cannot decrement. The current count is 0.');

        return new Count($this->value() - 1);
    }

    public function value(): int
    {
        return $this->value;
    }
}
