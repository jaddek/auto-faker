<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Attribute;

use Jaddek\AutoFaker\IFakerAttribute;

/**
 * @psalm-api
 */
#[\Attribute(\Attribute::TARGET_PROPERTY)]
class NameFaker implements IFakerAttribute
{
    /**
     * @var array<string>
     */
    private array $data = [
        "Oliver", "Charlotte", "Liam", "Amelia", "Noah", "Isla",
        "Elijah", "Ava", "James", "Sophia", "Lucas", "Mia",
        "William", "Harper", "Benjamin", "Luna", "Henry", "Ella",
        "Theodore", "Grace", "Jack", "Chloe", "Leo", "Zoe",
        "Thomas", "Evie", "Alexander", "Willow", "Harrison", "Ivy"
    ];

    #[\Override]
    public function __invoke(): string
    {
        return array_rand(array_flip($this->data));
    }
}
