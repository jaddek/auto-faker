<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker;

use InvalidArgumentException;
use Closure;

/**
 * @psalm-suppress MixedPropertyFetch, MixedArgument, MixedAssignment, MixedMethodCall, MixedOperand
 * @psalm-api
 */
final class AutoFaker
{
    /**
     * @param IReportFactory $builder The factory responsible for creating reports (object definitions).
     * @param array<class-string<IReferenceAttribute>, Closure(): mixed> $middlewares
     */
    public function __construct(
        private readonly IReportFactory $builder,
        private array $middlewares = [],
    ) {
        $this->setReferenceMiddlewares($middlewares);
    }

    /**
     * @param array<class-string<IReferenceAttribute>, Closure(): mixed> $middlewares
     * @return void
     */
    public function setReferenceMiddlewares(array $middlewares): void
    {
        foreach ($middlewares as $key => $middleware) {
            /** @psalm-suppress DocblockTypeContradiction */
            if (!\is_string($key) || !\is_subclass_of($key, IReferenceAttribute::class)) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Middleware key "%s" must be a class-string of type %s.',
                        $key,
                        IReferenceAttribute::class
                    )
                );
            }

            /** @psalm-suppress DocblockTypeContradiction */
            if (!$middleware instanceof Closure) {
                throw new InvalidArgumentException(
                    sprintf(
                        'Middleware for "%s" must be an instance of \Closure, %s given.',
                        $key,
                        get_debug_type($middleware)
                    )
                );
            }
        }
        $this->middlewares = $middlewares;
    }

    /**
     * @param class-string<IReferenceAttribute> $key The class-string of the IReferenceAttribute to handle.
     * @param Closure(): mixed $middleware The Closure that generates the value for the reference.
     * @return void
     * @throws InvalidArgumentException If the key is not a valid class-string or the middleware is not a Closure.
     */
    public function addReferenceMiddleware(string $key, Closure $middleware): void
    {
        if (!\is_subclass_of($key, IReferenceAttribute::class)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Middleware key "%s" must be a class-string of type %s.',
                    $key,
                    IReferenceAttribute::class
                )
            );
        }
        $this->middlewares[$key] = $middleware;
    }

    /**
     * @template T of object
     * @param class-string<T> $className The fully qualified class name of the entity to generate.
     * @param int $entityCount The number of entities to generate.
     * @param Closure $closure
     * @return void
     */
    public function generate(
        string $className,
        int $entityCount,
        Closure $closure,
    ): void {
        if (!class_exists($className)) {
            throw new InvalidArgumentException(sprintf('Class "%s" does not exist.', $className));
        }

        foreach ($this->getGenerator($className, $entityCount) as $num => $entity) {
            $closure($num, $entity);
        }
    }

    /**
     * A simple generator for a range of numbers.
     *
     * @param int $end The upper bound of the range (inclusive).
     * @return \Generator<int, int> Yields integers from 1 to $end.
     * @psalm-suppress InvalidDocblock
     */
    private static function xrange(int $end): \Generator
    {
        foreach (range(1, $end) as $x) {
            yield $x;
        }
    }

    /**
     * Returns a generator that yields fake entities.
     *
     * @template T of object
     * @param class-string<T> $className The fully qualified class name of the entity to generate.
     * @param int $entityCount The number of entities to generate.
     * @return \Generator<int, T> Yields generated entities. The key of the generator is the entity number (1-based).
     * @throws InvalidArgumentException If the class name is not a valid class.
     */
    public function getGenerator(string $className, int $entityCount): \Generator
    {
        if (!class_exists($className)) {
            throw new InvalidArgumentException(sprintf('Class "%s" does not exist.', $className));
        }

        // @psalm-var class-string<T> $className
        $report = $this->builder->makeReport($className);
        $proto = new $className();

        foreach (self::xrange($entityCount) as $num) {
            $entity = clone $proto;

            foreach ($report as $object) {
                $this->invokeFaker($object, $entity);
            }

            yield $num => $entity;
        }
    }

    /**
     * Invokes the faker for a specific property and assigns the generated value to the entity.
     *
     * @param IValueObject $object The value object containing faker and setter information.
     * @param object $entity The entity to which the faked value will be assigned.
     * @return void
     * @psalm-suppress MixedMethodCall, MixedArgument, MixedAssignment
     */
    protected function invokeFaker(IValueObject $object, object $entity): void
    {
        /** @var Closure(): mixed $faker */
        $faker = $object->getFaker();
        $value = $faker();

        if ($faker instanceof IReferenceAttribute) {
            /** @var class-string<IReferenceAttribute> $fakerClass */
            $fakerClass = $faker::class;

            $closure = $this->middlewares[$fakerClass] ?? null;

            if ($closure instanceof Closure) {
                $value = $closure();
            }
        }

        /** @psalm-suppress InvalidMethodCall */
        $setterMethod = $object->getSetter();
        if (!method_exists($entity, $setterMethod)) {
            throw new InvalidArgumentException(
                sprintf(
                    'Setter method "%s" does not exist on entity of type "%s".',
                    $setterMethod,
                    get_debug_type($entity)
                )
            );
        }
        $entity->$setterMethod($value);
    }
}
