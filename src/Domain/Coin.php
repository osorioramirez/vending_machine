<?php

declare(strict_types=1);

namespace App\Domain;

use App\Core\Domain\Enum;
use Webmozart\Assert\Assert;

/**
 * @method static FIVE_CENTS(): self
 * @method static TEN_CENTS(): self
 * @method static TWENTY_FIVE_CENTS(): self
 * @method static ONE_UNIT(): self
 */
class Coin extends Enum
{
    private const FIVE_CENTS = 5;
    private const TEN_CENTS = 10;
    private const TWENTY_FIVE_CENTS = 25;
    private const ONE_UNIT = 100;

    /**
     * @return Money[]
     */
    public static function valuesToMoney(): array
    {
        return self::coinsToMoney(self::values());
    }

    /**
     * @param Coin[] $coins
     *
     * @return Money[]
     */
    public static function coinsToMoney(array $coins): array
    {
        return array_map(
            fn (self $coin): Money => $coin->toMoney(),
            $coins
        );
    }

    public static function assertIsValidStringValue(string $value): void
    {
        $coins = array_map(
            fn (Money $money): string => (string) $money,
            self::valuesToMoney()
        );

        Assert::oneOf($value, $coins, 'The coin must be one of: %2$s. Got: %s');
    }

    public static function fromString(string $value): self
    {
        return new self(Money::fromFloat((float) $value)->cents());
    }

    public function toMoney(): Money
    {
        return new Money($this->getValue());
    }
}
