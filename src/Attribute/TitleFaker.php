<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Attribute;

#[\Attribute(\Attribute::TARGET_PROPERTY)]
class TitleFaker extends NameFaker
{
    /**
     * @var array<string>
     */
    private array $data = [
        'Book of the Era',
        'Sunlight',
        'Ocean and Sun',
        'South',
        'Literature, Poetry, Poems',
        'The trip around some islands in nowhere',
        'Diary and food',
        'The star #1234',
        'Back in a day (1989)',
    ];

    public function __invoke(): string
    {
        return array_rand(array_flip($this->data));
    }
}
