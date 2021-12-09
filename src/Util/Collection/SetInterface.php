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

use IteratorAggregate;
use MueR\AdventOfCode\Util\PhpOption\Option;

interface SetInterface extends CollectionInterface, IteratorAggregate
{
    public function add(mixed $elem): void;

    public function addAll(array $elements): void;

    public function remove(mixed $elem): void;

    /**
     * Returns the first element in the collection if available.
     */
    public function first(): Option;

    /**
     * Returns the last element in the collection if available.
     */
    public function last(): Option;

    /**
     * Returns all elements in this Set.
     */
    public function all(): array;

    /**
     * Returns a new Set with all elements in reverse order.
     */
    public function reverse(): SetInterface;

    /**
     * Adds the elements of another Set to this Set.
     */
    public function addSet(SetInterface $set): SetInterface;

    /**
     * Returns a new Set by omitting the given number of elements from the beginning.
     *
     * If the passed number is greater than the available number of elements, all will be removed.
     */
    public function drop(int $number): SetInterface;

    /**
     * Returns a new Set by omitting the given number of elements from the end.
     *
     * If the passed number is greater than the available number of elements, all will be removed.
     */
    public function dropRight(int $number): SetInterface;

    /**
     * Returns a new Set by omitting elements from the beginning for as long as the callable returns true.
     *
     * @param callable $callable Receives the element to drop as first argument.
     */
    public function dropWhile(callable $callable): SetInterface;

    /**
     * Creates a new collection by taking the given number of elements from the beginning
     * of the current collection.
     *
     * If the passed number is greater than the available number of elements, then all elements
     * will be returned as a new collection.
     */
    public function take(int $number): CollectionInterface;

    /**
     * Creates a new collection by taking elements from the current collection
     * for as long as the callable returns true.
     */
    public function takeWhile(callable $callable): CollectionInterface;

    /**
     * Creates a new collection by applying the passed callable to all elements
     * of the current collection.
     */
    public function map(callable $callable): CollectionInterface;
}
