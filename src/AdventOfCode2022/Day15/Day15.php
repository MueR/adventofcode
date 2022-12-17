<?php
/**
 * Part of AdventOfCode 2022
 */


declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2022\Day15;

use Illuminate\Support\Collection;
use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 15 puzzle.
 *
 * @see https://adventofcode.com/2022/day/15
 */
class Day15 extends AbstractSolver
{
    private Collection $beacons;

    /**
     * Solver method for part 1 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/15
     */
    public function partOne(): int
    {
        $yLevel = 2000000;

        $beaconsOnYLevel = $this->beacons
            ->filter(fn (array $pair) => $pair[1][1] === $yLevel)
            ->unique(fn (array $pair) => $pair[1][0])
        ;

        $sensedLocations = $this->beacons
            ->map(fn (array $line) => [$line[0], $this->manhattanDistance($line[0], $line[1])])
            ->flatMap(fn (array $line) => $this->rangeOn($yLevel, ...$line))
            ->unique()
        ;

        return $sensedLocations->count() - $beaconsOnYLevel->count();
    }

    /**
     * Solver method for part 2 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/15#part2
     */
    public function partTwo(): int
    {
        $maxCoord = 4000000;
        $sensorsWithDistance = $this->beacons
            ->map(fn (array $line) => [$line[0], $this->manhattanDistance($line[0], $line[1])])
            ->all()
        ;

        $points = [];

        foreach ($sensorsWithDistance as $pair) {
            $point = $pair[0];
            $distance = $pair[1];
            for ($x = $point[0] - ($distance + 1); $x <= $point[0] + ($distance + 1); $x++) {
                $distanceRemaining = ($distance + 1) - abs($x - $point[0]);
                if ($distanceRemaining === 0) {
                    $points[$x][$point[1]] = 1;

                    continue;
                }

                $points[$x][$point[1] + $distanceRemaining] = 1;
                $points[$x][$point[1] - $distanceRemaining] = 1;
            }
        }

        $potentials = (new Collection($points))
            ->map(fn (array $column) => array_keys($column))
            ->map(fn (array $column) => array_filter($column, static fn (int $y) => $y >= 0 && $y <= $maxCoord))
            ->filter(fn (array $column, int $x) => $x >= 0 && $x <= $maxCoord)
            ->flatMap(fn (array $column, int $x) => array_map(static fn (int $y) => [$x, $y], $column))
        ;

        $lostBeacon = $potentials
            ->first(fn (array $position) => (new Collection($sensorsWithDistance))
                ->every(fn (array $pair) => $this->manhattanDistance($position, $pair[0]) > $pair[1])
            )
        ;

        return ($lostBeacon[0] * 4000000) + $lostBeacon[1];
    }

    protected function parse(): void
    {
        $this->beacons = (new Collection(explode("\n", $this->readText())))
            ->map(fn (string $line) => str_replace(['Sensor at x=', ' y=', ' closest beacon is at x='], '', $line))
            ->map(fn (string $line) => explode(':', $line))
            ->map(fn (array $line) => array_map(
                fn (string $coordinates) => array_map('intval', explode(',', $coordinates)),
                $line
            ))
        ;
    }

    private function manhattanDistance(array $position1, array $position2): int
    {
        return abs($position2[0] - $position1[0]) + abs($position2[1] - $position1[1]);
    }

    private function rangeOn(int $yLevel, array $startPosition, int $radius): array
    {
        $verticalDistanceToYLevel = abs($startPosition[1] - $yLevel);
        if ($verticalDistanceToYLevel > $radius) {
            return [];
        }

        $radiusOnYLevel = $radius - $verticalDistanceToYLevel;

        return range($startPosition[0] - $radiusOnYLevel, $startPosition[0] + $radiusOnYLevel);
    }
}

