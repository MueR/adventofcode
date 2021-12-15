<?php

namespace MueR\AdventOfCode\AdventOfCode2021\Day15;

use MueR\AdventOfCode\Util\Vector;

class Grid
{
    private int $width;
    private int $height;
    /** @var array<array<int, int>> */
    private array $totalRiskLevels = [];
    /** @var array<array<int>> */
    private array $queue = [];

    /** @param array<int, array<int, int>> $grid */
    public function __construct(private array $grid)
    {
        $this->height = count($this->grid);
        $this->width = count($this->grid[0]);
    }

    public function display(): void
    {
        foreach ($this->totalRiskLevels as $y => $points) {
            foreach ($points as $x => $point) {
                printf("%1d", $this->getRisk($x, $y));
            }
            print "\n";
        }
    }

    public function solve(int $times = 1, bool $display = false): int
    {
        $width = $this->width * $times;
        $height = $this->height * $times;
        $deltas = [[-1, 0], [0, -1], [1, 0], [0, 1]];
        $this->totalRiskLevels = array_fill_keys(range(0, $height - 1), array_fill_keys(range(0, $width - 1), 0));

        $this->queue = [[0, 0]];
        while (count($this->queue) > 0) {
            $point = array_shift($this->queue);
            foreach ($deltas as $delta) {
                $newX = $point[0] + $delta[0];
                $newY = $point[1] + $delta[1];
                if (
                    !array_key_exists($newY, $this->totalRiskLevels) ||
                    !array_key_exists($newX, $this->totalRiskLevels[$newY])
                ) {
                    continue;
                }
                $this->calculateRisk($this->totalRiskLevels[$newY][$point[0]], [$newX, $newY]);
            }
        }

        if ($display) {
            $this->display();
        }

        return $this->totalRiskLevels[$height - 1][$width - 1];
    }

    private function getRisk(int $x, int $y): int
    {
        $width = $this->width;
        $height = $this->height;

        $tileIncrement = floor($x / $width) + floor($y / $height);
        $return = $this->grid[$y % $height][$x % $width] + $tileIncrement;
        if ($return > 9) {
            return ($return % 9);
        }

        return (int) $return;
    }

    private function calculateRisk(int $currentRisk, array $nextPoint): void
    {
        $newRisk = $currentRisk + $this->getRisk($nextPoint[0], $nextPoint[1]);
        $currentTotalAtVector = $this->totalRiskLevels[$nextPoint[1]][$nextPoint[0]];
        if ($currentTotalAtVector === 0 || $newRisk < $currentTotalAtVector) {
            $this->totalRiskLevels[$nextPoint[1]][$nextPoint[0]] = $newRisk;
            $this->queue[] = $nextPoint;
        }
    }
}
