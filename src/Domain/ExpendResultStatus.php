<?php

declare(strict_types=1);

namespace App\Domain;

use App\Core\Domain\Enum;

/**
 * @method static EXPENDED(): self
 * @method static NOT_CHANGE(): self
 * @method static NOT_AVAILABLE(): self
 * @method static NOT_ENOUGH_AMOUNT(): self
 */
class ExpendResultStatus extends Enum
{
    private const EXPENDED = 'EXPENDED';
    private const NOT_CHANGE = 'NOT_CHANGE';
    private const NOT_AVAILABLE = 'NOT_AVAILABLE';
    private const NOT_ENOUGH_AMOUNT = 'NOT_ENOUGH_AMOUNT';
}
