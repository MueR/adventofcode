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
        return count(array_filter(
            $this->input,
            static fn (array $elves) =>
                ($elves[0][0] >= $elves[1][0] && $elves[0][1] <= $elves[1][1]) ||
                ($elves[1][0] >= $elves[0][0] && $elves[1][1] <= $elves[0][1])
        ));
    }

    /**
     * Solver method for part 2 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/4#part2
     */
    public function partTwo(): int
    {
        return count(array_filter(
            $this->input,
            static fn (array $elves) => $elves[0][0] <= $elves[1][1] && $elves[1][0] <= $elves[0][1]
        ));
    }

    protected function parse(): void
    {
        $this->input = array_map(
            static function (string $line) {
                [$elfOne, $elfTwo] = explode(',', $line);
                $elfOne = array_map(static fn ($number) => (int) $number, explode('-', $elfOne));
                $elfTwo = array_map(static fn ($number) => (int) $number, explode('-', $elfTwo));

                return [$elfOne, $elfTwo];
            },
            explode("\n", $this->readText())
        );
    }
}
