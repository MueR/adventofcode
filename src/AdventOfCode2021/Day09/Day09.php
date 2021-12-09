<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day09;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 9 puzzle.
 *
 * @property array{int} $input
 */
class Day09 extends AbstractSolver
{
    public array $points = [];
    /** @var Position[] */
    protected array $lowestPoints = [];

    public function partOne() : int
    {
        $lowPoints = 0;
        foreach ($this->points as $x => $points) {
            foreach ($points as $y => $position) {
                if ($position->isLowest($position->getNeighbours($this->points), $position->value)) {
                    $this->lowestPoints[] = $position;
                    $lowPoints += $position->value + 1;
                }
            }
        }

        return $lowPoints;
    }

    public function partTwo() : int
    {
        $result = [];
        foreach ($this->lowestPoints as $position) {
            $result[] = $position->findBasin($this->points);
        }
        sort($result, SORT_DESC);

        return array_product(array_slice($result, -3));
    }

    protected function parse(): void
    {
        $lines = explode("\n", $this->readText());
        $this->points = [];
        foreach ($lines as $row => $line) {
            foreach (str_split($line) as $col => $value) {
                $this->points[$row][$col] = new Position($row, $col, (int) $value);
            }
        }
    }
}

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

    public function findBasin(array $grid, array $inBasin = []): int
    {
        if ($this->value === 9 || $grid[$this->x][$this->y]->inBasin) {
            return 0;
        }

        $grid[$this->x][$this->y]->inBasin = true;
        $notInBasin = array_filter($this->getNeighbours($grid), static fn (Position $pos) => !$pos->inBasin);

        return 1 + (int) array_sum(array_map(static fn (Position $position) => $position->findBasin($grid), $notInBasin));
    }
}
