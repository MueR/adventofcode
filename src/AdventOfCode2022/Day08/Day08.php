<?php
/**
 * Part of AdventOfCode 2022
 */


declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2022\Day08;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 8 puzzle.
 *
 * @see https://adventofcode.com/2022/day/8
 */
class Day08 extends AbstractSolver
{
    protected array $trees = [];
    protected array $scene = [];

    /**
     * Solver method for part 1 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/8
     */
    public function partOne() : int
    {
        $visible = 2 * count($this->trees[0]) + (2 * (count($this->trees) - 2));
        $yMax = count($this->trees) - 1;
        $xMax = count($this->trees[0]) - 1;
        for ($y = 1; $y < $yMax; $y++) {
            for ($x = 1; $x < $xMax; $x++) {
                if ($this->isVisible($x, $y)) {
                    $visible++;
                }
            }
        }
        return $visible;
    }

    /**
     * Solver method for part 2 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/8#part2
     */
    public function partTwo() : int
    {
        return -1;
    }

    protected function parse(): void
    {
        $this->test = true;
        $this->trees = array_map(
            static fn (string $line) => array_map(static fn (string $tree) => (int) $tree, str_split($line)),
            explode(PHP_EOL, $this->readText())
        );
    }

    private function isVisible(int $x, int $y): bool
    {
        $horziontal = $this->trees[$y];
        $vertical = [];
        foreach ($this->trees as $i => $row) {
            $vertical[] = $this->trees[$i][$x];
        }
        $left = array_slice($horziontal, 0, $x);
        $right = array_slice($horziontal, $x + 1);
        $top = array_slice($vertical, 0, $y);
        $bottom = array_slice($vertical, $y + 1);

        foreach ([$left, $right, $top, $bottom] as $set) {
            $result = count(array_filter(
                $set,
                function (int $height) use ($x, $y) {
                    return $height >= $this->trees[$y][$x];
                }
            ));
            if ($result === 0) {
                return true;
            }
        }
        return false;
    }
}
