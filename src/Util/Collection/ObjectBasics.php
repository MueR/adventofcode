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

interface ObjectBasics
{
    /**
     * Produces a hash for the given object.
     *
     * If two objects are equal (as per the equals() method), the hash() method must produce
     * the same hash for them.
     *
     * The reverse can, but does not necessarily have to be true. That is, if two objects have the
     * same hash, they do not necessarily have to be equal, but the equals() method must be called
     * to be sure.
     *
     * When implementing this method try to use a simple and fast algorithm that produces reasonably
     * different results for non-equal objects, and shift the heavy comparison logic to equals().
     */
    public function hash(): string|int;


    /**
     * Whether two objects are equal.
     *
     * This can compare by referential equality (===), or in case of value objects like (\DateTime) compare
     * the individual properties of the objects; it's up to the implementation.
     */
    public function equals(ObjectBasics $other): bool;
}
