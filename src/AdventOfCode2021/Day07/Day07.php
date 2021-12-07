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

    public function partOne() : int
    {
        $min = array_map(function ($position) {
            return $this->align($this->positions, $position, fn(int $current, int $to) => abs($current - $to));
        }, array_unique($this->positions));
        $finalPosition = array_search(min($min), $min, true);

        return $min[$finalPosition];
    }

    public function partTwo() : int
    {
        $average = array_sum($this->positions) / count($this->positions);
        $calc = function (int $current, int $to) {
            $distance = abs($current - $to);
            return $distance * ($distance + 1) / 2;
        };
        $min = $this->align($this->positions, (int)floor($average), $calc);
        $max = $this->align($this->positions, (int)ceil($average), $calc);

        return min($min, $max);
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
    }
}

