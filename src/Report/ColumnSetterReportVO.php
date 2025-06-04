<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Report;

use Jaddek\AutoFaker\IFakerAttribute;
use Jaddek\AutoFaker\IValueObject;

final readonly class ColumnSetterReportVO implements IValueObject
{
    /**
     * @psalm-api
     *
     * @param IFakerAttribute $faker
     * @param string $setter
     */
    public function __construct(
        private IFakerAttribute $faker,
        private string $setter,
    ) {
    }


    /**
     * @return IFakerAttribute
     */
    #[\Override]
    public function getFaker(): IFakerAttribute
    {
        return $this->faker;
    }

    #[\Override]
    public function getSetter(): string
    {
        return $this->setter;
    }

    public function __toString(): string
    {
        return sprintf('%s via %s', $this->faker::class, $this->setter);
    }
}
