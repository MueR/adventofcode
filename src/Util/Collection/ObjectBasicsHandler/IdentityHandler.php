<?php

namespace MueR\AdventOfCode\Util\Collection\ObjectBasicsHandler;

use MueR\AdventOfCode\Util\Collection\ObjectBasicsHandler;

class IdentityHandler implements ObjectBasicsHandler
{
    public function hash(object $object): string|int
    {
        return spl_object_hash($object);
    }

    public function equals(object $firstObject, object $secondObject): bool
    {
        return $firstObject === $secondObject;
    }
}
