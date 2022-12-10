<?php

namespace MueR\AdventOfCode\Util;

class Move implements \Stringable
{
    public function __construct(public readonly string $direction, public readonly int $steps)
    {
    }

    public function __toString(): string
    {
        return sprintf('Move<%s,%d>', $this->direction, $this->steps);
    }

    public static function fromString(string $line): self
    {
        return new self($line[0], (int) substr($line, 2));
    }
}
