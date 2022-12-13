<?php
/**
 * Part of AdventOfCode 2022
 */

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2022\Day01;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 1 puzzle.
 *
 * @property array{int} $input
 * @see https://adventofcode.com/2022/day/1
 */
class Day01 extends AbstractSolver
{
    private array $calories = [];

    /**
     * Solver method for part 1 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/1
     */
    public function partOne(): int
    {
        return $this->calories[0];
    }

    /**
     * Solver method for part 2 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/1#part2
     */
    public function partTwo(): int
    {
        return array_sum(array_slice($this->calories, 0, 3));
    }

    protected function parse(): void
    {
        $this->calories = array_map(
            static fn (string $set) => array_sum(array_map(
                static fn (string $calories) => (int) $calories,
                explode(PHP_EOL, $set)
            )),
            explode(PHP_EOL . PHP_EOL, $this->readText())
        );
        rsort($this->calories, SORT_NUMERIC);
    }
}

