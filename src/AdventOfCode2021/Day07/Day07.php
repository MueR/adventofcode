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
        $average = $this->positions[count($this->positions) / 2];

        return $this->align($this->positions, $average, fn (int $position, int $to) => (int) abs($position - $average));
    }

    public function partTwo() : int
    {
        $average = array_sum($this->positions) / count($this->positions);
        $calc = static function (int $current, int $to) {
            $distance = abs($current - $to);
            return $distance * ($distance + 1) / 2;
        };

        return min(
            $this->align($this->positions, (int)floor($average), $calc),
            $this->align($this->positions, (int)ceil($average), $calc)
        );
    }

    public function align(array $positions, int $to, callable $fuelCalculator): int
    {
        $fuel = 0;
        foreach ($positions as $current) {
            $fuel += $fuelCalculator($current, $to);
        }

        return $fuel;
    }

    protected function parse(): void
    {
        $this->positions = array_map(static fn (string $pos) => (int) $pos, explode(',', $this->readText()));
        natsort($this->positions);
    }
}

