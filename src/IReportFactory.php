<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker;

interface IReportFactory
{
    /**
     * @template T of object
     * @param class-string<T> $className The fully qualified class name for which to generate the report.
     * @return array<array-key, IValueObject> An array where each element is an IValueObject,
     */
    public function makeReport(
        string $className,
    ): array;
}
