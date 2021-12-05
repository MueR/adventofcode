<?php

namespace MueR\AdventOfCode\Util;

class Cube
{
    public function __construct(public int $x, public int $y, public int $z)
    {
    }

    public function getCoordinates(): array
    {
        return [$this->x, $this->y, $this->z];
    }

    public function setCoordinates(int $x, int $y, int $z): void
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }
}
