<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day15;

use JetBrains\PhpStorm\Pure;
use MueR\AdventOfCode\AbstractSolver;
use MueR\AdventOfCode\Util\Vector;

/**
 * Day 15 puzzle.
 *
 * @property array{int} $input
 */
class Day15 extends AbstractSolver
{
    protected array $grid;

    public function partOne(): int
    {
        return (new Grid($this->grid))->solve();
    }

    public function partTwo(): int
    {
        return (new Grid($this->grid))->solve(5);
    }

    protected function parse(): void
    {
        $lines = explode("\n", $this->readText());
        $this->grid = array_map(static fn (string $line) => array_map(static fn (string $char) => (int) $char, str_split($line)), $lines);
    }
}

class Grid
{
    private int $width;
    private int $height;
    private array $totalRiskLevels = [];

    public function __construct(private array $grid)
    {
        $this->height = count($this->grid);
        $this->width = count($this->grid[0]);
    }

    public function display(): void
    {
        foreach ($this->totalRiskLevels as $y => $points) {
            foreach ($points as $x => $point) {
                printf("%1d", $this->getRisk(new Vector($x, $y)));
            }
            print "\n";
        }
    }

    public function solve(int $times = 1, bool $display = false): int
    {
        $width = $this->width * $times;
        $height = $this->height * $times;

        $this->totalRiskLevels = array_fill_keys(range(0, $height - 1), array_fill_keys(range(0, $width - 1), 0));
        $toProcess = [new Vector(0, 0)];
        while (count($toProcess) > 0) {
            $point = array_shift($toProcess);
            $currentRisk = $this->totalRiskLevels[$point->y][$point->x];

            $deltas = [[-1, 0], [0, -1], [1, 0], [0, 1]];
            foreach ($deltas as $delta) {
                if (
                    !array_key_exists($point->y + $delta[1], $this->totalRiskLevels) ||
                    !array_key_exists($point->x + $delta[0], $this->totalRiskLevels[$point->y + $delta[1]])
                ) {
                    continue;
                }
                $this->calculateRisk($currentRisk, new Vector($point->x + $delta[0], $point->y + $delta[1]), $toProcess);
            }
        }

        if ($display) {
            $this->display();
        }

        return $this->totalRiskLevels[$height - 1][$width - 1];
    }

    private function getRisk(Vector $point): int
    {
        $width = $this->width;
        $height = $this->height;

        $tileIncrement = floor($point->x / $width) + floor($point->y / $height);
        $return = $this->grid[$point->y % $height][$point->x % $width] + $tileIncrement;
        if ($return > 9) {
            return ($return % 9);
        }

        return (int) $return;
    }

    private function calculateRisk(int $currentRisk, Vector $nextPoint, array &$toProcess): void
    {
        $newRisk = $currentRisk + $this->getRisk($nextPoint);
        $currentTotalAtVector = $this->totalRiskLevels[$nextPoint->y][$nextPoint->x];
        if ($currentTotalAtVector === 0 || $newRisk < $currentTotalAtVector) {
            $this->totalRiskLevels[$nextPoint->y][$nextPoint->x] = $newRisk;
            $toProcess[] = $nextPoint;
        }
    }
}
