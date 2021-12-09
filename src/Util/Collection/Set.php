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
use LogicException;
use MueR\AdventOfCode\Util\PhpOption\None;
use MueR\AdventOfCode\Util\PhpOption\Option;
use MueR\AdventOfCode\Util\PhpOption\Some;

class Set implements SetInterface
{
    public const ELEM_TYPE_SCALAR = 1;
    public const ELEM_TYPE_OBJECT = 2;
    public const ELEM_TYPE_OBJECT_WITH_HANDLER = 3;

    private ?int $elementType;

    private array $elements = [];

    private int $elementCount = 0;

    private array $lookup = [];

    public function __construct(array $elements = [])
    {
        $this->addAll($elements);
    }

    public function add(mixed $elem): void
    {
        if ($this->elementType === null) {
            if ($elem instanceof ObjectBasics) {
                $this->addObject($elem);
            } elseif (is_scalar($elem)) {
                $this->addScalar($elem);
            } elseif (is_object($elem)) {
                $this->addObjectWithHandler($elem, ObjectBasicsHandlerRegistry::getHandler(get_class($elem)));
            }
            throw new LogicException(sprintf('The type of $elem ("%s") is not supported in sets.', gettype($elem)));
        }
        if ($this->elementType === self::ELEM_TYPE_OBJECT) {
            if ($elem instanceof ObjectBasics) {
                $this->addObject($elem);

                return;
            }

            if (is_object($elem)) {
                throw new LogicException(sprintf('This Set already contains object implement ObjectBasics, and cannot be mixed with objects that do not implement this interface like "%s".', get_class($elem)));
            }

            throw new LogicException(sprintf('This Set already contains objects, and cannot be mixed with elements of type "%s".', gettype($elem)));
        }
        if ($this->elementType === self::ELEM_TYPE_OBJECT_WITH_HANDLER) {
            if (is_object($elem)) {
                $this->addObjectWithHandler($elem, ObjectBasicsHandlerRegistry::getHandler(get_class($elem)));

                return;
            }

            throw new LogicException(sprintf('This Set already contains object with an external handler, and cannot be mixed with elements of type "%s".', gettype($elem)));
        }
        if ($this->elementType === self::ELEM_TYPE_SCALAR) {
            if (is_scalar($elem)) {
                $this->addScalar($elem);

                return;
            }

            throw new LogicException(sprintf('This Set already contains scalars, and cannot be mixed with elements of type "%s".', gettype($elem)));
        }
        throw new LogicException('Unknown element type in Set - should never be reached.');
    }

    public function addSet(SetInterface $set): SetInterface
    {
        $this->addAll($set->all());

        return $this;
    }

    public function addAll(array $elements): void
    {
        foreach ($elements as $elem) {
            $this->add($elem);
        }
    }

    public function remove(mixed $elem): void
    {
        switch ($this->elementType) {
            case self::ELEM_TYPE_OBJECT:
                if ($elem instanceof ObjectBasics) {
                    $this->removeObject($elem);
                }
                break;
            case self::ELEM_TYPE_OBJECT_WITH_HANDLER:
                if (is_object($elem)) {
                    $this->removeObjectWithHandler($elem, ObjectBasicsHandlerRegistry::getHandler(get_class($elem)));
                }
                break;
            case self::ELEM_TYPE_SCALAR:
                if (is_scalar($elem)) {
                    $this->removeScalar($elem);
                }
                break;
            default:
                throw new InvalidArgumentException(sprintf("Invalid argument type supplied. Expected object or scalar, got '%s'.", gettype($elem)));
        }
    }

    public function first(): Option
    {
        if (empty($this->elements)) {
            return None::create();
        }

        return new Some(reset($this->elements));
    }

    public function last(): Option
    {
        if (empty($this->elements)) {
            return None::create();
        }

        return new Some(end($this->elements));
    }

    public function all(): array
    {
        return array_values($this->elements);
    }

    public function reverse(): SetInterface
    {
        return $this->createNew(array_reverse($this->elements));
    }

    public function drop(int $number): SetInterface
    {
        if ($number <= 0) {
            throw new InvalidArgumentException(sprintf('The number must be greater than 0, but got %d.', $number));
        }

        return $this->createNew(array_slice($this->elements, $number));
    }

    public function dropRight(int $number): SetInterface
    {
        if ($number <= 0) {
            throw new InvalidArgumentException(sprintf('The number must be greater than 0, but got %d.', $number));
        }

        return $this->createNew(array_slice($this->elements, 0, -1 * $number));
    }

    public function dropWhile(callable $callable): SetInterface
    {
        /** @noinspection ForeachInvariantsInspection */
        for ($i = 0, $count = count($this->elements); $i < $count; $i++) {
            if (true !== $callable($this->elements[$i])) {
                break;
            }
        }

        return $this->createNew(array_slice($this->elements, $i));
    }

    public function take(int $number): CollectionInterface
    {
        if ($number <= 0) {
            throw new InvalidArgumentException(sprintf('$number must be greater than 0, but got %d.', $number));
        }

        return $this->createNew(array_slice($this->elements, 0, $number));
    }

    public function takeWhile(callable $callable): CollectionInterface
    {
        $newElements = [];

        foreach ($this->elements as $i => $iValue) {
            if ($callable($this->elements[$i]) !== true) {
                break;
            }

            $newElements[] = $iValue;
        }

        return $this->createNew($newElements);
    }

    public function map(callable $callable): CollectionInterface
    {
        $newElements = [];
        foreach ($this->elements as $i => $element) {
            $newElements[$i] = $callable($element);
        }

        return $this->createNew($newElements);
    }

    public function contains(mixed $searchedElement): bool
    {
        if ($this->elementType === self::ELEM_TYPE_OBJECT) {
            if ($searchedElement instanceof ObjectBasics) {
                return $this->containsObject($searchedElement);
            }

            return false;
        }

        if ($this->elementType === self::ELEM_TYPE_OBJECT_WITH_HANDLER) {
            if (is_object($searchedElement)) {
                return $this->containsObjectWithHandler($searchedElement, ObjectBasicsHandlerRegistry::getHandler(get_class($searchedElement)));
            }

            return false;
        }

        if ($this->elementType === self::ELEM_TYPE_SCALAR) {
            if (is_scalar($searchedElement)) {
                return $this->containsScalar($searchedElement);
            }

            return false;
        }

        return false;
    }

    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    public function filter(callable $callable): CollectionInterface
    {
        return $this->filterInternal($callable, true);
    }

    public function filterNot(callable $callable): CollectionInterface
    {
        return $this->filterInternal($callable, false);
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

    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator(array_values($this->elements));
    }

    public function count(): int
    {
        return count($this->elements);
    }

    protected function createNew(array $elements): static
    {
        return new static($elements);
    }

    private function filterInternal($callable, $booleanKeep): static
    {
        $newElements = [];
        foreach ($this->elements as $element) {
            if ($booleanKeep !== $callable($element)) {
                continue;
            }

            $newElements[] = $element;
        }

        return $this->createNew($newElements);
    }

    private function containsScalar($elem): bool
    {
        if (!isset($this->lookup[$elem])) {
            return false;
        }

        foreach ($this->lookup[$elem] as $index) {
            if ($elem === $this->elements[$index]) {
                return true;
            }
        }

        return false;
    }

    private function containsObjectWithHandler($object, ObjectBasicsHandler $handler): bool
    {
        $hash = $handler->hash($object);
        if (!isset($this->lookup[$hash])) {
            return false;
        }

        foreach ($this->lookup[$hash] as $index) {
            if ($handler->equals($object, $this->elements[$index])) {
                return true;
            }
        }

        return false;
    }

    private function containsObject(ObjectBasics $object): bool
    {
        $hash = $object->hash();
        if (!isset($this->lookup[$hash])) {
            return false;
        }

        foreach ($this->lookup[$hash] as $index) {
            if ($object->equals($this->elements[$index])) {
                return true;
            }
        }

        return false;
    }

    private function removeScalar($elem): void
    {
        if (!isset($this->lookup[$elem])) {
            return;
        }

        foreach ($this->lookup[$elem] as $k => $index) {
            if ($elem === $this->elements[$index]) {
                $this->removeElement($elem, $k, $index);
                break;
            }
        }
    }

    private function removeObjectWithHandler($object, ObjectBasicsHandler $handler): void
    {
        $hash = $handler->hash($object);
        if (!isset($this->lookup[$hash])) {
            return;
        }

        foreach ($this->lookup[$hash] as $k => $index) {
            if ($handler->equals($object, $this->elements[$index])) {
                $this->removeElement($hash, $k, $index);
                break;
            }
        }
    }

    private function removeObject(ObjectBasics $object): void
    {
        $hash = $object->hash();
        if (!isset($this->lookup[$hash])) {
            return;
        }

        foreach ($this->lookup[$hash] as $k => $index) {
            if ($object->equals($this->elements[$index])) {
                $this->removeElement($hash, $k, $index);
                break;
            }
        }
    }

    private function removeElement($hash, $lookupIndex, $storageIndex): void
    {
        unset($this->lookup[$hash][$lookupIndex]);
        if (empty($this->lookup[$hash])) {
            unset($this->lookup[$hash]);
        }

        unset($this->elements[$storageIndex]);
    }

    private function addScalar($elem): void
    {
        if (isset($this->lookup[$elem])) {
            foreach ($this->lookup[$elem] as $index) {
                if ($this->elements[$index] === $elem) {
                    return; // Already exists.
                }
            }
        }

        $this->insertElement($elem, $elem);
        $this->elementType = self::ELEM_TYPE_SCALAR;
    }

    private function addObjectWithHandler($object, ObjectBasicsHandler $handler): void
    {
        $hash = $handler->hash($object);
        if (isset($this->lookup[$hash])) {
            foreach ($this->lookup[$hash] as $index) {
                if ($handler->equals($object, $this->elements[$index])) {
                    return; // Already exists.
                }
            }
        }

        $this->insertElement($object, $hash);
        $this->elementType = self::ELEM_TYPE_OBJECT_WITH_HANDLER;
    }

    private function addObject(ObjectBasics $elem): void
    {
        $hash = $elem->hash();
        if (isset($this->lookup[$hash])) {
            foreach ($this->lookup[$hash] as $index) {
                if ($elem->equals($this->elements[$index])) {
                    return; // Element already exists.
                }
            }
        }

        $this->insertElement($elem, $hash);
        $this->elementType = self::ELEM_TYPE_OBJECT;
    }

    private function insertElement(mixed $elem, string $hash): void
    {
        $index = $this->elementCount++;
        $this->elements[$index] = $elem;
        $this->lookup[$hash][] = $index;
    }
}
