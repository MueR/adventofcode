<?php

namespace MueR\AdventOfCode\Util;

class Line
{
    public function __construct(public Vector $start, public Vector $end)
    {
    }

    public function isHorizontal(): bool
    {
        return $this->start->x === $this->end->x;
    }

    public function isVertical(): bool
    {
        return $this->start->y === $this->end->y;
    }

    public function pointsOnLine(): array
    {
        $points = [];
        $current = clone $this->start;
        $stepX = Util::sign($this->end->x - $this->start->x);
        $stepY = Util::sign($this->end->y - $this->start->y);

        while (!$current->equals($this->end)) {
            $points[] = clone $current;
            $current->move($stepX, $stepY);
        }
        $points[] = $this->end;

        return $points;
    }
}
