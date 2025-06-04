<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Attribute;

use Jaddek\AutoFaker\IFakerAttribute;

/**
 * @psalm-api
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
final readonly class UniqidFaker implements IFakerAttribute
{
    #[\Override]
    public function __invoke(): string
    {
        return uniqid();
    }
}
