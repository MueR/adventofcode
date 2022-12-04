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
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace MueR\AdventOfCode\Util\Collection;

use ArrayIterator;
use InvalidArgumentException;
use IteratorAggregate;
use JetBrains\PhpStorm\Pure;
use MueR\AdventOfCode\Util\PhpOption\None;
use MueR\AdventOfCode\Util\PhpOption\Option;
use MueR\AdventOfCode\Util\PhpOption\Some;

abstract class AbstractMap extends AbstractCollection implements IteratorAggregate, MapInterface
{
    private const INVALID_NUMBER_ERROR = 'The number must be greater than 0, but got %d.';
    protected array $elements;

    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }

    public function set(mixed $key, mixed $value): void
    {
        $this->elements[$key] = $value;
    }

    public function exists(callable $callable): bool
    {
        foreach ($this as $k => $v) {
            if ($callable($k, $v) === true) {
                return true;
            }
        }

        return false;
    }

    /**
     * Sets all key/value pairs in the map.
     */
    public function setAll(array $kvMap): void
    {
        $this->elements = array_merge($this->elements, $kvMap);
    }

    public function addMap(MapInterface $map): MapInterface
    {
        foreach ($map as $k => $v) {
            $this->elements[$k] = $v;
        }

        return $this;
    }

    public function get(mixed $key): Option
    {
        if (isset($this->elements[$key])) {
            return new Some($this->elements[$key]);
        }

        return None::create();
    }

    public function all(): array
    {
        return $this->elements;
    }

    public function remove($key): mixed
    {
        if (!isset($this->elements[$key])) {
            throw new InvalidArgumentException(sprintf('The map has no key named "%s".', $key));
        }

        $element = $this->elements[$key];
        unset($this->elements[$key]);

        return $element;
    }

    public function clear(): void
    {
        $this->elements = [];
    }

    public function first(): Option
    {
        if (empty($this->elements)) {
            return None::create();
        }

        $elem = reset($this->elements);

        return new Some([key($this->elements), $elem]);
    }

    public function last(): Option
    {
        if (empty($this->elements)) {
            return None::create();
        }

        $elem = end($this->elements);

        return new Some([key($this->elements), $elem]);
    }

    public function contains(mixed $searchedElement): bool
    {
        foreach ($this->elements as $existingElem) {
            if ($existingElem === $searchedElement) {
                return true;
            }
        }

        return false;
    }

    public function containsKey(mixed $key): bool
    {
        return isset($this->elements[$key]);
    }

    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    /**
     * Returns a new filtered map.
     *
     * @param callable $callable receives the element.
     */
    public function filter(callable $callable): AbstractMap
    {
        return $this->filterInternal($callable, true);
    }

    /**
     * Returns a new filtered map.
     *
     * @param callable $callable receives the element.
     */
    public function filterNot(callable $callable): AbstractMap
    {
        return $this->filterInternal($callable, false);
    }

    private function filterInternal(callable $callable, bool $booleanKeep): static
    {
        $newElements = [];
        foreach ($this->elements as $k => $element) {
            if ($booleanKeep !== $callable($element)) {
                continue;
            }

            $newElements[$k] = $element;
        }

        return $this->createNew($newElements);
    }

    public function foldLeft(mixed $initialValue, callable $callable): mixed
    {
        $value = $initialValue;
        foreach ($this->elements as $elem) {
            $value = $callable($value, $elem);
        }

        return $value;
    }

    public function foldRight(mixed $initialValue, callable $callable): mixed
    {
        $value = $initialValue;
        foreach (array_reverse($this->elements) as $elem) {
            $value = $callable($elem, $value);
        }

        return $value;
    }

    public function dropWhile(callable $callable): MapInterface
    {
        $newElements = [];
        $stopped = false;
        foreach ($this->elements as $k => $v) {
            if (!$stopped) {
                if ($callable($k, $v) === true) {
                    continue;
                }

                $stopped = true;
            }

            $newElements[$k] = $v;
        }

        return $this->createNew($newElements);
    }

    public function drop(int $number): MapInterface
    {
        if ($number <= 0) {
            throw new InvalidArgumentException(sprintf(self::INVALID_NUMBER_ERROR, $number));
        }

        return $this->createNew(array_slice($this->elements, $number, null, true));
    }

    public function dropRight(int $number): MapInterface
    {
        if ($number <= 0) {
            throw new InvalidArgumentException(sprintf(self::INVALID_NUMBER_ERROR, $number));
        }

        return $this->createNew(array_slice($this->elements, 0, -1 * $number, true));
    }

    public function take(int $number): MapInterface
    {
        if ($number <= 0) {
            throw new InvalidArgumentException(sprintf(self::INVALID_NUMBER_ERROR, $number));
        }

        return $this->createNew(array_slice($this->elements, 0, $number, true));
    }

    public function takeWhile(callable $callable): MapInterface
    {
        $newElements = [];
        foreach ($this->elements as $k => $v) {
            if ($callable($k, $v) !== true) {
                break;
            }

            $newElements[$k] = $v;
        }

        return $this->createNew($newElements);
    }

    public function find(callable $callable): Option
    {
        foreach ($this->elements as $k => $v) {
            if ($callable($k, $v) === true) {
                return new Some([$k, $v]);
            }
        }

        return None::create();
    }

    public function keys(): array
    {
        return array_keys($this->elements);
    }

    public function values(): array
    {
        return array_values($this->elements);
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->elements);
    }

    #[Pure] protected function createNew(array $elements): static
    {
        return new static($elements);
    }
}
