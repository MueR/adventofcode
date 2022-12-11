<?php
/**
 * Part of AdventOfCode 2022
 */


declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2022\Day06;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 6 puzzle.
 *
 * @see https://adventofcode.com/2022/day/6
 */
class Day06 extends AbstractSolver
{
    private string $buffer;

    /**
     * Solver method for part 1 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/6
     */
    public function partOne() : int
    {
        return $this->findUnique(4);
    }

    /**
     * Solver method for part 2 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/6#part2
     */
    public function partTwo() : int
    {
        return $this->findUnique(14);
    }

    protected function findUnique(int $numCharacters): int
    {
        $last = '';
        foreach (str_split($this->buffer) as $i => $char) {
            $last = substr($char . $last, 0, $numCharacters);
            if (count(array_unique(str_split($last))) === $numCharacters) {
                return $i + 1;
            }
        }

        throw new \LogicException('Could not find a unique number of characters.');
    }

    protected function parse(): void
    {
        $this->buffer = $this->readText();
    }
}

