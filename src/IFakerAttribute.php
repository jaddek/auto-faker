<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker;

interface IFakerAttribute
{
    public function __invoke(): mixed;
}