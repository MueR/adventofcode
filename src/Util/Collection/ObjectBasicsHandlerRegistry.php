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

use LogicException;
use MueR\AdventOfCode\Util\Collection\ObjectBasicsHandler\DateTimeHandler;
use MueR\AdventOfCode\Util\Collection\ObjectBasicsHandler\IdentityHandler;

abstract class ObjectBasicsHandlerRegistry
{
    private static array $handlers = [
        'DateTime' => DateTimeHandler::class,
    ];

    private static ObjectBasicsHandler $defaultObjectHandler;

    private static array $aliases = [];

    /**
     * Defines an alias.
     *
     * $aliasClass must be a sub-type (extend or implement) $handlingClass; otherwise you will run into trouble.
     *
     * Aliases can only be one level deep,
     *
     *    i.e. aliasClass -> handlingClass is supported,
     *    but  aliasClass -> anotherAliasClass -> handlingClass is not.
     */
    public static function addAliasFor(string $handlingClass, string $aliasClass): void
    {
        self::$aliases[$handlingClass] = $aliasClass;
    }

    public static function addHandlerFor(string $handlingClass, string|ObjectBasicsHandler $handlerInstanceOrClassName): void
    {
        if (!$handlerInstanceOrClassName instanceof ObjectBasicsHandler && !is_string($handlerInstanceOrClassName)) {
            throw new LogicException('$handler must be an instance of ObjectBasicsHandler, or a string referring to the handlers class.');
        }

        self::$handlers[$handlingClass] = $handlerInstanceOrClassName;
    }

    public static function getHandler($className): ObjectBasicsHandler
    {
        if (isset(self::$aliases[$className])) {
            $className = self::$aliases[$className];
        }

        if (!isset(self::$handlers[$className])) {
            if (self::$defaultObjectHandler === null) {
                self::$defaultObjectHandler = new IdentityHandler();
            }

            return self::$defaultObjectHandler;
        }

        if (self::$handlers[$className] instanceof ObjectBasicsHandler) {
            return self::$handlers[$className];
        }

        if (is_string(self::$handlers[$className])) {
            $handlerClass = self::$handlers[$className];

            return self::$handlers[$className] = new $handlerClass();
        }

        throw new LogicException(sprintf(
            'Unknown handler type ("%s") for class "%s" - should never be reached.',
            gettype(self::$handlers[$className]),
            $className
        ));
    }

    final private function __construct()
    {
    }
}
