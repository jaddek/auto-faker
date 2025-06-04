<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Attribute;

use Jaddek\AutoFaker\IFakerAttribute;
use Random\RandomException;

/**
 * @psalm-api
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
readonly class IntFaker implements IFakerAttribute
{
    public function __construct(
        private int $min = PHP_INT_MIN,
        private int $max = PHP_INT_MAX,
        private bool $positive = true
    ) {
    }

    /**
     * @throws RandomException
     */
    #[\Override]
    public function __invoke(): int
    {
        return random_int($this->positive ? 0 : $this->min, $this->max);
    }
}
