<?php

declare(strict_types=1);

/*
 * Copyright 2012 Johannes M. Schmitt <schmittjoh@gmail.com>
 * Modifications by MueR (c) 2021
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace MueR\AdventOfCode\Util\PhpOption;


use Exception;
use InvalidArgumentException;
use RuntimeException;
use Traversable;

/**
 * @template T
 *
 * @extends Option<T>
 */
final class LazyOption extends Option
{
    /** @var callable(mixed...):(Option<T>) */
    private $callback;

    /** @var array<int, mixed> */
    private array $arguments;

    /** @var Option<T>|null */
    private ?Option $option;

    /**
     * @template S
     *
     * @param callable(mixed...):(Option<S>) $callback
     * @param array<int, mixed> $arguments
     *
     * @return LazyOption<S>
     */
    public static function create(callable $callback, array $arguments = []): self
    {
        return new self($callback, $arguments);
    }

    /**
     * @param callable(mixed...):(Option<T>) $callback
     * @param array<int, mixed> $arguments
     */
    public function __construct(callable $callback, array $arguments = [])
    {
        if (!is_callable($callback)) {
            throw new InvalidArgumentException('Invalid callback given');
        }

        $this->callback = $callback;
        $this->arguments = $arguments;
    }

    public function isDefined(): bool
    {
        return $this->option()->isDefined();
    }

    public function isEmpty(): bool
    {
        return $this->option()->isEmpty();
    }

    public function get()
    {
        return $this->option()->get();
    }

    public function getOrElse($default)
    {
        return $this->option()->getOrElse($default);
    }

    public function getOrCall($callable)
    {
        return $this->option()->getOrCall($callable);
    }

    public function getOrThrow(Exception $ex)
    {
        return $this->option()->getOrThrow($ex);
    }

    public function orElse(Option $else): Option
    {
        return $this->option()->orElse($else);
    }

    public function ifDefined($callable): void
    {
        $this->option()->forAll($callable);
    }

    public function forAll($callable): Option
    {
        return $this->option()->forAll($callable);
    }

    public function map(callable $callable): Option
    {
        return $this->option()->map($callable);
    }

    public function flatMap(callable $callable): Option
    {
        return $this->option()->flatMap($callable);
    }

    public function filter(callable $callable): Option
    {
        return $this->option()->filter($callable);
    }

    public function filterNot(callable $callable): Option
    {
        return $this->option()->filterNot($callable);
    }

    public function select(mixed $value): Option
    {
        return $this->option()->select($value);
    }

    public function reject(mixed $value): Option
    {
        return $this->option()->reject($value);
    }

    /**
     * @return Traversable<T>
     * @throws Exception
     */
    public function getIterator(): Traversable
    {
        return $this->option()->getIterator();
    }

    public function foldLeft(mixed $initialValue, callable $callable)
    {
        return $this->option()->foldLeft($initialValue, $callable);
    }

    public function foldRight(mixed $initialValue, callable $callable)
    {
        return $this->option()->foldRight($initialValue, $callable);
    }

    /**
     * @return Option<T>
     */
    private function option(): Option
    {
        if (null === $this->option) {
            /** @var mixed */
            $option = call_user_func_array($this->callback, $this->arguments);
            if ($option instanceof Option) {
                $this->option = $option;
            } else {
                throw new RuntimeException(sprintf('Expected instance of %s', Option::class));
            }
        }

        return $this->option;
    }
}
