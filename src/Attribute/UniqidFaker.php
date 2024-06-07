<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Attribute;

use Jaddek\AutoFaker\IFakerAttribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
readonly class UniqidFaker implements IFakerAttribute
{
    public function __invoke(): string
    {
        return uniqid();
    }
}