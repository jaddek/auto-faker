<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Report;

use Jaddek\AutoFaker\Exception;
use Jaddek\AutoFaker\IReportFactory;
use Jaddek\AutoFaker\IFakerAttribute;
use Jaddek\AutoFaker\IValueObject;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;
use ReflectionAttribute;

/**
 * @psalm-api
 */
final readonly class ColumnSetterBuilder implements IReportFactory
{
    /**
     * @var ColumnSetterReflectionHandler
     */
    private ColumnSetterReflectionHandler $handler;

    public function __construct(ColumnSetterReflectionHandler $handler)
    {
        $this->handler = $handler;
    }

    /**
     * @param class-string $className
     * @return list<IValueObject>
     * @throws ReflectionException
     */
    #[\Override]
    public function makeReport(string $className): array
    {
        $generator = $this->handler->getReflectionProperties($className);

        /** @var list<IValueObject> $report */
        $report = [];

        foreach ($generator as $class => $property) {
            try {
                $report[] = $this->buildReportLine(
                    $class,
                    $property,
                );
            } catch (Exception) {
                continue;
            }
        }

        return $report;
    }

    /**
     * @param ReflectionClass<object> $class
     * @param ReflectionProperty $property
     * @return ColumnSetterReportVO
     * @throws Exception
     */
    protected function buildReportLine(
        ReflectionClass    $class,
        ReflectionProperty $property,
    ): ColumnSetterReportVO
    {
        $setter = $this->handler->getSetter($class, $property);

        $attributes = $property->getAttributes(
            IFakerAttribute::class,
            ReflectionAttribute::IS_INSTANCEOF
        );

        if (empty($attributes)) {
            throw new Exception('No IFakerAttribute found on property ' . $property->getName());
        }

        $attribute = $attributes[0]->newInstance();

        return new ColumnSetterReportVO(
            $attribute,
            $setter,
        );
    }
}
