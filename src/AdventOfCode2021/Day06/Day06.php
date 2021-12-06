<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day06;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 6 puzzle.
 *
 * @property array{int} $input
 */
class Day06 extends AbstractSolver
{
    protected array $fish;

    public function partOne(int $days = 80) : int
    {
        for ($i = 0; $i < $days; $i++) {
            $this->day();
        }

        return array_sum($this->fish);
    }

    public function partTwo() : int
    {
        $this->parse();

        return $this->partOne(256);
    }

    protected function day(): void
    {
        $newFish = $this->fish[0];
        for ($i = 1; $i < 9; $i++) {
            $this->fish[$i - 1] = $this->fish[$i];
        }
        $this->fish[6] += $newFish;
        $this->fish[8] = $newFish;
    }

    protected function parse(): void
    {
        $this->fish = array_fill_keys(range(0, 8), 0);
        foreach (explode(',', $this->readText()) as $state) {
            $this->fish[(int) $state]++;
        }
    }
}
