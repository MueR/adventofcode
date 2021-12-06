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

    public function partOne() : int
    {
        $total = 0;
        foreach ($this->fish as $fish) {
            $total += $this->reproduce($fish, 80);
        }

        return $total;
    }

    public function partTwo() : int
    {
        return $this->reproduce(1, 256);
        $total = 0;
        $results = [];
        foreach (array_unique($this->fish) as $state) {
            $results[$state] = $this->reproduce($state, 256);
        }
        foreach ($this->fish as $state) {
            $total += $results[$state];
        }

        return $total;
    }

    protected function reproduce(int $state, int $days): int
    {
        $result = 1;
        if ($state > $days) {
            return $result;
        }
        for ($range = [], $i = $days - $state; $i > 0; $i -= 7) {
            $range[] = $i;
        }
        foreach ($range as $day) {
            print $day . "\n";
            $result += $this->reproduce(8, $day - 1);
        }

        return $result;
    }

    protected function parse(): void
    {
        $this->fish = array_map(static fn (string $number) => (int)$number, explode(',', $this->readText()));
    }
}

