<?php

namespace MueR\AdventOfCode\AdventOfCode2021\Day09;

class Position
{
    protected array $neighbours = [];
    public bool $inBasin = false;

    public function __construct(public int $x, public int $y, public int $value)
    {
    }

    public function getNeighbours(array $grid): array
    {
        if (empty($this->neighbours)) {
            foreach ([[-1, 0], [0, -1], [1, 0], [0, 1]] as $delta) {
                if (isset($grid[$this->x + $delta[0]][$this->y + $delta[1]])) {
                    $this->neighbours[] = $grid[$this->x + $delta[0]][$this->y + $delta[1]];
                }
            }
        }

        return $this->neighbours;
    }

    public function isLowest(array $neighbours, int $value): bool
    {
        return empty(array_filter($neighbours, static fn (Position $position) => $position->value <= $value));
    }

    public function findBasin(array $grid): int
    {
        if ($this->value === 9 || $grid[$this->x][$this->y]->inBasin) {
            return 0;
        }

        $grid[$this->x][$this->y]->inBasin = true;
        $notInBasin = array_filter($this->getNeighbours($grid), static fn (Position $pos) => !$pos->inBasin);

        return 1 + (int) array_sum(array_map(
            static fn (Position $position) => $position->findBasin($grid),
            $notInBasin
        ));
    }
}
