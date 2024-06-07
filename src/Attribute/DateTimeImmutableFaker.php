<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Attribute;

use Jaddek\AutoFaker\IFakerAttribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class DateTimeImmutableFaker implements IFakerAttribute
{
    public function __invoke(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }
}
