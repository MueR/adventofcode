<?php

namespace MueR\AdventOfCode\AdventOfCode2022\Day12;

use MueR\AdventOfCode\Util\Point;

class ElevatedPoint extends Point
{
    public function __construct(int $x, int $y, public readonly ?int $value = null)
    {
        parent::__construct($x, $y);
    }

    public function canMove(self $point): bool
    {
        return $this->value - $point->value <= 1;
    }

    /**
     * @return ElevatedPoint[]
     */
    public function getNeighbours(?array $grid = null): array
    {
        $neighbours = [];
        foreach (parent::getNeighbours($grid) as $neighbour) {
            /** @var ElevatedPoint $neighbour */
            if ($this->canMove($neighbour)) {
                $neighbours[] = $neighbour;
            }
        }
        return $neighbours;
    }
}
