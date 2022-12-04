<?php
/**
 * Part of AdventOfCode 2022
 */


declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2022\Day03;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 3 puzzle.
 *
 * @property array{int} $input
 * @see https://adventofcode.com/2022/day/3
 */
class Day03 extends AbstractSolver
{
    /**
     * Solver method for part 1 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/3
     */
    public function partOne(): int
    {
        $sum = 0;
        foreach ($this->input as $line) {
            $compSize = strlen($line) / 2;
            [$compOne, $compTwo] = str_split($line, $compSize);
            $items = array_unique(str_split($compOne));
            foreach ($items as $item) {
                if (str_contains($compTwo, $item)) {
                    $sum += $this->getPriority($item);
                }
            }
        }

        return $sum;
    }

    /**
     * Solver method for part 2 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/3#part2
     */
    public function partTwo(): int
    {
        $sum = 0;
        for ($i = 0, $max = count($this->input); $i < $max; $i += 3) {
            $lineOne = str_split($this->input[$i]);
            $lineTwo = $this->input[$i+1];
            $lineThree = $this->input[$i+2];

            foreach ($lineOne as $item) {
                if (str_contains($lineTwo, $item) && str_contains($lineThree, $item)) {
                    $sum += $this->getPriority($item);
                    break;
                }
            }
        }
        return $sum;
    }

    protected function parse(): void
    {
        $this->input = explode("\n", $this->readText());
    }

    private function getPriority(string $item): int
    {
        $code = ord($item);
        if ($code >= ord('a')) {
            return $code - ord('a') + 1;
        }
        return $code - ord('A') + 27;
    }
}
