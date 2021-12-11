<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day11;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 11 puzzle.
 *
 * @property array{int} $input
 */
class Day11 extends AbstractSolver
{
    /** @var array<int, array<int, Octopus>> */
    private array $octopuses = [];

    public function partOne(): int
    {
        $result = 0;
        for ($i = 0; $i < 100; $i++) {
            $result += $this->step();
        }

        return $result;
    }

    public function partTwo(): int
    {
        $this->parse();

        $result = 1;
        $number = array_sum(array_map(static fn (array $line) => count($line), $this->octopuses));
        while ($this->step() < $number) {
            $result++;
        }

        return $result;
    }

    protected function step(): int
    {
        $this->walk(fn (Octopus $octopus) => $octopus->increase());
        $this->walk(function (Octopus $octopus) {
            if ($octopus->energyLevel > 9 && !$octopus->hasFlashed) {
                $octopus->flash();
            }
        });
        $result = $this->walk(fn (Octopus $octopus) => $octopus->flashed());

        return count($result);
    }

    protected function walk(callable $callable): mixed
    {
        $return = [];
        foreach ($this->octopuses as $x => $octopuses) {
            foreach ($octopuses as $y => $octopus) {
                $return[] = $callable($octopus);
            }
        }

        return array_filter($return);
    }

    protected function parse(): void
    {
        $lines = explode("\n", $this->readText());
        foreach ($lines as $x => $line) {
            foreach (str_split($line) as $y => $energy) {
                $this->octopuses[$x][$y] = new Octopus($x, $y, (int) $energy);
            }
        }

        $this->walk(fn (Octopus $octopus) => $octopus->findNeighbours($this->octopuses));
    }
}
