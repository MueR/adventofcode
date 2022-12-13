<?php
/**
 * Part of AdventOfCode 2022
 */


declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2022\Day13;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 13 puzzle.
 *
 * @see https://adventofcode.com/2022/day/13
 */
class Day13 extends AbstractSolver
{
    private array $pairs;

    /**
     * Solver method for part 1 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/13
     */
    public function partOne(): int
    {
        $result = 0;
        foreach ($this->pairs as $i => $lists) {
            if ($this->compareList(...$lists) > 0) {
                $result += $i + 1;
            }
        }

        return $result;
    }

    /**
     * Solver method for part 2 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/13#part2
     */
    public function partTwo(): int
    {
        $targetOne = [[2]];
        $targetTwo = [[6]];
        $items = [$targetOne, $targetTwo];
        foreach ($this->pairs as $pair) {
            $items[] = $pair[0];
            $items[] = $pair[1];
        }
        usort($items, [$this, 'compareList']);
        $items = array_reverse($items);
        $found = [array_search($targetOne, $items) + 1, array_search($targetTwo, $items) + 1];

        return array_product($found);
    }

    protected function parse(): void
    {
        $input = explode(PHP_EOL . PHP_EOL, $this->readText());
        $this->pairs = [];
        foreach ($input as $set) {
            [$left, $right] = array_map(
                fn (string $packets) => json_decode($packets, true, 512, JSON_THROW_ON_ERROR),
                explode(PHP_EOL, $set)
            );
            $this->pairs[] = [$left, $right];
        }
    }

    private function compareList(array|int $left, array|int $right): int
    {
        if (is_int($left) && is_int($right)) {
            return $right - $left;
        }
        if (is_int($left) || is_int($right)) {
            if (empty([$left])) {
                // Really PHP, I shouldn't have to check for empty arrays here...
                return 1;
            }

            return $this->compareList((array) $left, (array) $right);
        }

        foreach ($left as $i => $v) {
            if (!isset($right[$i])) {
                return -1;
            }
            $c = $this->compareList($v, $right[$i]);
            if ($c !== 0) {
                return $c;
            }
        }
        foreach ($right as $i => $v) {
            if (!isset($left[$i])) {
                return 1;
            }
        }

        return count($left) - count($right);
    }
}

