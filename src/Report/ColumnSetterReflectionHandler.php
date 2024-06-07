<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Report;

use Generator;
use Jaddek\AutoFaker\Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

readonly class ColumnSetterReflectionHandler
{
    /**
     * @param ReflectionClass<object> $reflectionClass
     * @param ReflectionProperty $reflectionProperty
     * @return string
     * @throws Exception
     */
    public function getSetter(
        ReflectionClass $reflectionClass,
        ReflectionProperty $reflectionProperty
    ): string {
        $setter = $this->getSetterName(
            $reflectionProperty->getName()
        );

        if ($reflectionClass->hasMethod($setter) === false) {
            throw new Exception(sprintf(
                'Setter %s was not found in class %s',
                $setter,
                $reflectionClass->getName()
            ));
        }

        return $setter;
    }

    /**
     * @param class-string $className
     * @return Generator<ReflectionClass<object>, ReflectionProperty>
     * @throws ReflectionException
     */
    public function getReflectionProperties(
        string $className
    ): Generator {
        $reflectionClass = new ReflectionClass($className);

        foreach ($reflectionClass->getProperties() as $property) {
            yield $reflectionClass => $property;
        }
    }

    protected function getSetterName(string $name): string
    {
        return 'set' . ucfirst($name);
    }
}
