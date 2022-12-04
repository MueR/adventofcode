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

use MueR\AdventOfCode\Util\PhpOption\LazyOption;
use MueR\AdventOfCode\Util\PhpOption\None;
use MueR\AdventOfCode\Util\PhpOption\Option;
use MueR\AdventOfCode\Util\PhpOption\Some;

abstract class AbstractCollection
{
    public function contains(mixed $searchedElement): bool
    {
        foreach ($this as $element) {
            if ($element === $searchedElement) {
                return true;
            }
        }

        return false;
    }

    public function find(callable $callable): Option
    {
        $self = $this;

        return new LazyOption(function () use ($callable, $self) {
            foreach ($self as $element) {
                if ($callable($element) === true) {
                    return new Some($element);
                }
            }

            return None::create();
        });
    }
}
