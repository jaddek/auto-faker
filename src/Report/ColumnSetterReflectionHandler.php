<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker\Report;

use Generator;
use Jaddek\AutoFaker\Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionProperty;

final readonly class ColumnSetterReflectionHandler
{
    /**
     * Returns the name of the setter method for a property.
     *
     * @param string $name
     * @return string
     */
    protected function getSetterName(string $name): string
    {
        return 'set' . ucfirst($name);
    }

    /**
     * Returns the name of a valid setter method from a class for a given property.
     *
     * @param ReflectionClass<object> $reflectionClass
     * @param ReflectionProperty $reflectionProperty
     * @return string
     * @throws Exception If setter method is not found in the class.
     */
    public function getSetter(
        ReflectionClass $reflectionClass,
        ReflectionProperty $reflectionProperty
    ): string {
        $propertyName = $reflectionProperty->getName();
        $setter = $this->getSetterName($propertyName);

        if (!$reflectionClass->hasMethod($setter)) {
            throw new Exception(sprintf(
                'Setter "%s" not found in class %s.',
                $setter,
                $reflectionClass->getName()
            ));
        }

        return $setter;
    }

    /**
     * Yields each property of the given class as a pair of ReflectionClass and ReflectionProperty.
     *
     * @param class-string $className
     * @return Generator<ReflectionClass<object>, ReflectionProperty>
     * @throws ReflectionException
     */
    public function getReflectionProperties(string $className): Generator
    {
        $reflectionClass = new ReflectionClass($className);

        foreach ($reflectionClass->getProperties() as $property) {
            yield $reflectionClass => $property;
        }
    }
}
