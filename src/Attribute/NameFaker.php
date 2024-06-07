<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Attribute;

use Jaddek\AutoFaker\IFakerAttribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class NameFaker implements IFakerAttribute
{
    /**
     * @var array<string>
     */
    private array $data = [
        'Andrew',
        'Jase',
        'John',
        'Matt',
        'Anton',
        'Tom',
        'Benjamin',
        'Alice',
        'Mavis',
        'Valentina',
        'Julia',
        'Alicia',
        'Karen',
        'Ksenia'
    ];

    public function __invoke(): string
    {
        return array_rand(array_flip($this->data));
    }
}