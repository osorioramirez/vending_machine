<?php

declare(strict_types=1);

namespace App\Domain;

use App\Core\Domain\Enum;

/**
 * @method static WATER(): self
 * @method static JUICE(): self
 * @method static SODA(): self
 */
class ItemName extends Enum
{
    private const WATER = 'WATER';
    private const JUICE = 'JUICE';
    private const SODA = 'SODA';
}
