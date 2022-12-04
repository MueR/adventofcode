<?php
/**
 * Part of AdventOfCode 2022
 */


declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2022\Day04;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 4 puzzle.
 *
 * @property array{int} $input
 * @see https://adventofcode.com/2022/day/4
 */
class Day04 extends AbstractSolver
{
    /**
     * Solver method for part 1 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/4
     */
    public function partOne(): int
    {
        $fullOverlap = 0;
        foreach ($this->input as $index => [$elfOne, $elfTwo]) {
            if (
                ($elfOne[0] >= $elfTwo[0] && $elfOne[1] <= $elfTwo[1]) ||
                ($elfTwo[0] >= $elfOne[0] && $elfTwo[1] <= $elfOne[1])
            ) {
                $fullOverlap++;
            }
        }
        return $fullOverlap;
    }

    /**
     * Solver method for part 2 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/4#part2
     */
    public function partTwo(): int
    {
        $partialOverlap = 0;
        foreach ($this->input as [$elfOne, $elfTwo]) {
            if ($elfOne[0] <= $elfTwo[1] && $elfTwo[0] <= $elfOne[1]) {
                $partialOverlap++;
            }
        }
        return $partialOverlap;
    }

    protected function parse(): void
    {
        $this->input = array_map(
            static function (string $line) {
                [$elfOne, $elfTwo] = explode(',', $line);
                $elfOne = array_map(static fn ($number) => (int)$number, explode('-', $elfOne));
                $elfTwo = array_map(static fn ($number) => (int)$number, explode('-', $elfTwo));
                return [$elfOne, $elfTwo];
            },
            explode("\n", $this->readText())
        );
    }
}
