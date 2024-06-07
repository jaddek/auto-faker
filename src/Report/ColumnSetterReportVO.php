<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Report;

use Jaddek\AutoFaker\IFakerAttribute;
use Jaddek\AutoFaker\IValueObject;

readonly class ColumnSetterReportVO implements IValueObject
{
    public function __construct(
        private IFakerAttribute $faker,
        private string          $setter,
    )
    {

    }


    /**
     * @return IFakerAttribute
     */
    public function getFaker(): IFakerAttribute
    {
        return $this->faker;
    }

    public function getSetter(): string
    {
        return $this->setter;
    }
}