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

    protected function printGrid(): void
    {
        foreach ($this->octopuses as $x => $octopuses) {
            foreach ($octopuses as $y => $octopus) {
                print $octopus->energyLevel;
            }
            print "\n";
        }
        print "\n";
    }
}

class Octopus
{
    /** @var Octopus[] */
    public array $neighbours = [];

    public function __construct(public int $x, public int $y, public int $energyLevel, public bool $hasFlashed = false)
    {
    }

    public function increase(): void
    {
        $this->energyLevel++;
    }

    public function flash(): void
    {
        $this->energyLevel = 0;
        $this->hasFlashed = true;
        foreach($this->neighbours as $neighbour) {
            $neighbour->increase();
            if ($neighbour->energyLevel > 9) {
                $neighbour->flash();
            }
        }
    }

    public function flashed(): ?int
    {
        if ($this->hasFlashed || $this->energyLevel > 9) {
            $this->energyLevel = 0;
            $this->hasFlashed = false;
            return 1;
        }
        $this->hasFlashed = false;
        return null;
    }

    public function findNeighbours(array $grid): void
    {
        $deltas = [[-1, 0], [0, -1], [1, 0], [0, 1], [-1, -1], [1, -1], [-1, 1], [1, 1]];
        foreach ($deltas as $delta) {
            $findX = $this->x + $delta[0];
            $findY = $this->y + $delta[1];
            if (!array_key_exists($findX, $grid)) {
                continue;
            }
            if (!array_key_exists($findY, $grid[$findX])) {
                continue;
            }
            $this->neighbours[] = $grid[$findX][$findY];
        }
    }
}

