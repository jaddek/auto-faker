<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Report;

use Jaddek\AutoFaker\Exception;
use Jaddek\AutoFaker\IReportFactory;
use Jaddek\AutoFaker\IFakerAttribute;
use Jaddek\AutoFaker\IValueObject;

readonly class ColumnSetterBuilder implements IReportFactory
{
    public function __construct(
        private ColumnSetterReflectionHandler $handler,
    )
    {

    }

    /**
     * @param class-string $className
     * @return array|IValueObject[]
     * @throws \ReflectionException
     */
    public function makeReport(
        string $className,
    ): array
    {
        $generator = $this->handler->getReflectionProperties($className);

        foreach ($generator as $class => $property) {
            try {
                $report[] = $this->getReportLine(
                    $class,
                    $property,
                );
            } catch (Exception) {
                continue;
            }
        }

        return $report ?? [];
    }

    /**
     * @param \ReflectionClass<object> $reflectionClass
     * @param \ReflectionProperty $reflectionProperty
     * @return ColumnSetterReportVO
     * @throws Exception
     */
    protected function getReportLine(
        \ReflectionClass    $reflectionClass,
        \ReflectionProperty $reflectionProperty,
    ): ColumnSetterReportVO
    {
        $setter = $this->handler->getSetter($reflectionClass, $reflectionProperty);

        $attributes = $reflectionProperty->getAttributes(
            IFakerAttribute::class,
            \ReflectionAttribute::IS_INSTANCEOF
        );

        if (empty($attributes)) {
            throw new Exception('Attributes not found.');
        }

        return new ColumnSetterReportVO(
            $attributes[0]->newInstance(),
            $setter,
        );
    }
}