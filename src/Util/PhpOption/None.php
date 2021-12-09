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

use EmptyIterator;
use Exception;
use JetBrains\PhpStorm\Pure;
use RuntimeException;

final class None extends Option
{
    private static None $instance;

    public static function create(): Option
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function get(): void
    {
        throw new RuntimeException('None has no value.');
    }

    public function getOrCall($callable)
    {
        return $callable();
    }

    public function getOrElse($default)
    {
        return $default;
    }

    /**
     * @throws Exception
     */
    public function getOrThrow(Exception $ex): void
    {
        throw $ex;
    }

    public function isEmpty(): bool
    {
        return true;
    }

    public function isDefined(): bool
    {
        return false;
    }

    public function orElse(Option $else): Option
    {
        return $else;
    }

    public function ifDefined($callable): void
    {
        // Just do nothing in that case.
    }

    public function forAll($callable): Option
    {
        return $this;
    }

    public function map(callable $callable): Option
    {
        return $this;
    }

    public function flatMap(callable $callable): Option
    {
        return $this;
    }

    public function filter(callable $callable): Option
    {
        return $this;
    }

    public function filterNot(callable $callable): Option
    {
        return $this;
    }

    public function select(mixed $value): Option
    {
        return $this;
    }

    public function reject(mixed $value): Option
    {
        return $this;
    }

    #[Pure] public function getIterator(): EmptyIterator
    {
        return new EmptyIterator();
    }

    public function foldLeft(mixed $initialValue, callable $callable)
    {
        return $initialValue;
    }

    public function foldRight(mixed $initialValue, callable $callable)
    {
        return $initialValue;
    }

    private function __construct()
    {
    }
}
