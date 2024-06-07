<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker;

interface IReportFactory
{
    /**
     * @param string $className
     * @return array<IValueObject>
     */
    public function makeReport(
        string $className,
    ): array;
}
