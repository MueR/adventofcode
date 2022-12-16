<?php
/**
 * Part of AdventOfCode 2022
 */


declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2022\Day14;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 14 puzzle.
 *
 * @see https://adventofcode.com/2022/day/14
 */
class Day14 extends AbstractSolver
{
    /**
     * Solver method for part 1 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/14
     */
    public function partOne() : int
    {
        $cave = new Cave($this->readText());
        while (true) {
            $endPosition = $cave->moveSand([500, 0]);
            if ($endPosition === false) {
                break;
            }
            $cave->addSand($endPosition);
        }

        return $cave->getSandCount();
    }

    /**
     * Solver method for part 2 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/14#part2
     */
    public function partTwo() : int
    {
        $cave = new Cave($this->readText());
        while (true) {
            $endPosition = $cave->moveSandWithFloor([500, 0]);
            $cave->addSand($endPosition);
            if ($endPosition === [500, 0]) {
                break;
            }
        }

        return $cave->getSandCount();
    }
}

