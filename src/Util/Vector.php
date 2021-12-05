<?php

namespace MueR\AdventOfCode\Util;

class Vector
{
    public function __construct(public int $x, public int $y)
    {
    }

    public function equals(Vector $vector): bool
    {
        return $this->x === $vector->x && $this->y === $vector->y;
    }

    public function move($x, $y): void
    {
        $this->x += $x;
        $this->y += $y;
    }

    public function __toString(): string
    {
        return $this->x . ',' . $this->y;
    }

    public static function fromString(string $string): self
    {
        [$x, $y] = explode(',', $string);

        return new static((int) $x, (int) $y);
    }
}
