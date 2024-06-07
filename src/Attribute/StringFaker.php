<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Attribute;

use Jaddek\AutoFaker\IFakerAttribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
readonly class StringFaker implements IFakerAttribute
{
    public function __construct(
        private int $length = 255
    ) {
    }

    public function __invoke(): string
    {
        $string = sha1((string)time());

        $length = strlen($string);

        return $length <= $this->length ? $string : substr($string, 0, $this->length);
    }
}
