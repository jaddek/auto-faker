<?php

declare(strict_types=1);

namespace Jaddek\AutoFaker;

class AutoFaker
{
    public function __construct(
        private readonly IReportFactory $builder,
        /**
         * @var array<\Closure>
         */
        private array $middlewares = [],
    ) {
    }

    /**
     * @param array<\Closure> $middlewares
     * @return void
     */
    public function setReferenceMiddlewares(array $middlewares): void
    {
        $this->middlewares = $middlewares;
    }

    public function addReferenceMiddleware(array $middleware): void
    {
        $this->middlewares = array_merge($this->middlewares, $middleware);
    }


    /**
     */
    public function generate(
        string $className,
        int $entityCount,
        \Closure $closure,
    ): void {
        foreach ($this->getGenerator($className, $entityCount) as $num => $entity) {
            $closure($num, $entity);
        }
    }

    private static function xrange(int $end): \Generator
    {
        foreach (range(1, $end) as $x) {
            yield $x;
        }
    }

    /**
     */
    public function getGenerator(string $className, int $entityCount): \Generator
    {
        $report = $this->builder->makeReport($className);
        $proto  = new $className();
        foreach (self::xrange($entityCount) as $ignored) {
            $entity = clone $proto;

            foreach ($report as $object) {
                $this->invokeFaker($object, $entity);
            }

            yield $entity;
        }
    }

    /**
     */
    protected function invokeFaker(IValueObject $object, object $entity): void
    {
        $faker = $object->getFaker();
        $value = $faker();

        if ($faker instanceof IReferenceAttribute) {
            $closure = $this->middlewares[$faker::class] ?? null;

            if ($closure instanceof \Closure) {
                $value = $closure();
            }
        }

        $entity->{$object->getSetter()}($value);
    }
}
