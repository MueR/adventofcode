<?php

namespace MueR\AdventOfCode\AdventOfCode2021\Day11;

class Octopus
{
    /** @var Octopus[] */
    public array $neighbours = [];

    public function __construct(public int $x, public int $y, public int $energyLevel, public bool $hasFlashed = false)
    {
    }

    public function increase(): void
    {
        $this->energyLevel++;
    }

    public function flash(): void
    {
        $this->energyLevel = 0;
        $this->hasFlashed = true;
        foreach ($this->neighbours as $neighbour) {
            $neighbour->increase();
            if ($neighbour->energyLevel > 9) {
                $neighbour->flash();
            }
        }
    }

    public function flashed(): ?int
    {
        if ($this->hasFlashed || $this->energyLevel > 9) {
            $this->energyLevel = 0;
            $this->hasFlashed = false;

            return 1;
        }
        $this->hasFlashed = false;

        return null;
    }

    public function findNeighbours(array $grid): void
    {
        $deltas = [[-1, 0], [0, -1], [1, 0], [0, 1], [-1, -1], [1, -1], [-1, 1], [1, 1]];
        foreach ($deltas as $delta) {
            $findX = $this->x + $delta[0];
            $findY = $this->y + $delta[1];
            if (!array_key_exists($findX, $grid)) {
                continue;
            }
            if (!array_key_exists($findY, $grid[$findX])) {
                continue;
            }
            $this->neighbours[] = $grid[$findX][$findY];
        }
    }
}
