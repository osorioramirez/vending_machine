<?php

declare(strict_types=1);

namespace App\Domain;

use App\Core\Domain\Enum;

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
}
