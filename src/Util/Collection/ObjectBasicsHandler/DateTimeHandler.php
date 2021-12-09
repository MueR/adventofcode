<?php

namespace MueR\AdventOfCode\Util\Collection\ObjectBasicsHandler;

use DateTimeInterface;
use LogicException;
use MueR\AdventOfCode\Util\Collection\ObjectBasicsHandler;

class DateTimeHandler implements ObjectBasicsHandler
{
    public function hash(object $object): string|int
    {
        if (!$object instanceof DateTimeInterface) {
            throw new LogicException('$object must be an instance of \DateTimeInterface.');
        }

        return $object->getTimestamp();
    }

    public function equals(object $firstObject, object $secondObject): bool
    {
        if (!$firstObject instanceof DateTimeInterface) {
            throw new LogicException('$thisObject must be an instance of \DateTimeInterface.');
        }
        if (!$secondObject instanceof DateTimeInterface) {
            return false;
        }

        return $firstObject->format(DateTimeInterface::ATOM) === $secondObject->format(DateTimeInterface::ATOM);
    }
}
