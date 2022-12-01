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
        return array_sum($this->calories);
    }

    protected function parse(): void
    {
        $file = $this->getFile();

        $min = 0;
        $calories = 0;
        while (!feof($file)) {
            $line = fgets($file);

            if ($line !== PHP_EOL) {
                $calories += (int) $line;
                continue;
            }

            if ($calories > $min) {
                $l = count($this->calories);
                if ($l === 3) {
                    array_pop($this->calories);
                }
                $this->calories[] = $calories;
                rsort($this->calories, SORT_NUMERIC);
                $min = $this->calories[max(0, $l - 1)];
            }
            $calories = 0;
        }
        rsort($this->calories, SORT_NUMERIC);
    }
}

