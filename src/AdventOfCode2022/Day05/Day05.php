<?php
/**
 * Part of AdventOfCode 2022
 */


declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2022\Day05;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 5 puzzle.
 *
 * @see https://adventofcode.com/2022/day/5
 */
class Day05 extends AbstractSolver
{
    protected array $stacks = [];

    protected array $moves = [];

    /**
     * Solver method for part 1 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/5
     */
    public function partOne(): string
    {
        $stacks = $this->stacks;
        foreach ($this->moves as $move) {
            for ($i = 0; $i < $move['move']; $i++) {
                $item = array_pop($stacks[$move['from']]);
                $stacks[$move['to']][] = $item;
            }
        }

        return $this->getResult($stacks);
    }

    /**
     * Solver method for part 2 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/5#part2
     */
    public function partTwo(): string
    {
        foreach ($this->moves as $move) {
            $stack = [];
            for ($i = 0; $i < $move['move']; $i++) {
                $stack[] = array_pop($this->stacks[$move['from']]);
            }
            $stack = array_reverse($stack);
            foreach ($stack as $crate) {
                $this->stacks[$move['to']][] = $crate;
            }
        }

        return $this->getResult($this->stacks);
    }

    protected function parse(): void
    {
        [$field, $moves] = explode("\n\n", file_get_contents($this->getInputFileName()));
        $field = array_reverse(explode("\n", $field));
        array_shift($field);
        foreach ($field as $row) {
            $set = str_split($row, 4);
            foreach ($set as $i => $item) {
                $index = $i + 1;
                if (!array_key_exists($index, $this->stacks)) {
                    $this->stacks[$index] = [];
                }
                $item = trim($item, '[] ');
                if ($item !== '') {
                    $this->stacks[$index][] = $item;
                }
            }
        }

        $moves = explode("\n", $moves);
        foreach ($moves as $index => $move) {
            $actions = explode(' ', $move);
            for ($i = 0, $max = count($actions); $i < $max; $i += 2) {
                $this->moves[$index][($actions[$i])] = (int) $actions[$i + 1];
            }
        }
    }

    private function getResult(array $stacks): string
    {
        $result = '';
        foreach ($stacks as $stack) {
            $result .= $stack[count($stack) - 1];
        }

        return $result;
    }
}

