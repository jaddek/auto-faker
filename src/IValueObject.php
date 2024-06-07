<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker;

interface IValueObject
{
    public function getFaker(): IFakerAttribute;

    public function getSetter(): string;
}