<?php

namespace MueR\AdventOfCode\Util;

class Line
{
    private array $points = [];

    public function __construct(public Vector $start, public Vector $end)
    {
        $this->pointsOnLine();
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
        if (empty($this->points)) {
            $current = clone $this->start;
            $stepX = Util::sign($this->end->x - $this->start->x);
            $stepY = Util::sign($this->end->y - $this->start->y);

            while (!$current->equals($this->end)) {
                $this->points[] = clone $current;
                $current->move($stepX, $stepY);
            }
            $this->points[] = $this->end;
        }

        return $this->points;
    }
}
