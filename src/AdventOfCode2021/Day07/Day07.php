<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day07;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 7 puzzle.
 *
 * @property array{int} $input
 */
class Day07 extends AbstractSolver
{
    protected array $positions = [];
    protected array $perPosition = [];
    protected int $finalPosition;

    public function partOne() : int
    {
        $min = array_map(function ($position) {
            return $this->align($this->positions, $position, fn(int $current, int $to) => abs($current - $to));
        }, array_keys($this->perPosition));
        $this->finalPosition = array_search(min($min), $min, true);

        return $min[$this->finalPosition];
    }

    public function partTwo() : int
    {
        return $this->align($this->positions, $this->finalPosition, function (int $current, int $to) {
            return array_sum(range(1, abs($current - $to)));
        });
    }

    public function align(array $positions, int $to, callable $fuelCalculator)
    {
        $fuel = 0;
        foreach ($positions as $current) {
            $fuel += $fuelCalculator($current, $to);
        }

        return $fuel;
    }

    protected function parse(): void
    {
        $this->positions = array_map(fn (string $pos) => (int) $pos, explode(',', $this->readText()));
        $this->perPosition = array_fill_keys(range(min($this->positions), max($this->positions)), 0);
        foreach ($this->positions as $pos) {
            $this->perPosition[$pos]++;
        }
    }
}

