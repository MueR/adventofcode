<?php
/**
 * Part of AdventOfCode 2022
 */


declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2022\Day11;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 11 puzzle.
 *
 * @see https://adventofcode.com/2022/day/11
 */
class Day11 extends AbstractSolver
{
    /** @var Monkey[] */
    private array $monkeys = [];
    private array $inspections = [];

    /**
     * Solver method for part 1 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/11
     */
    public function partOne(): int
    {
        for ($i = 1; $i <= 20; $i++) {
            foreach ($this->monkeys as $monkey) {
                while (!$monkey->items->isEmpty()) {
                    $item = $monkey->items->pop();
                    $this->inspections[$monkey->number]++;
                    $item = (int) floor($monkey->operation($item) / 3);
                    $target = $item % $monkey->divisibleBy === 0
                        ? $monkey->monkeyIfTrue
                        : $monkey->monkeyIfFalse;
                    $this->monkeys[$target]->catch($item);
                }
            }
        }

        rsort($this->inspections, SORT_NUMERIC);

        return $this->inspections[0] * $this->inspections[1];
    }

    /**
     * Solver method for part 2 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/11#part2
     */
    public function partTwo(): int
    {
        $this->parse();
        $divTest = array_reduce(
            $this->monkeys,
            static fn (int $carry, Monkey $monkey) => $carry * $monkey->divisibleBy,
            1
        );
        for ($i = 1; $i <= 10000; $i++) {
            foreach ($this->monkeys as $monkey) {
                while (!$monkey->items->isEmpty()) {
                    $this->inspections[$monkey->number]++;
                    $item = $monkey->operation($monkey->items->pop()) % $divTest;
                    $target = $item % $monkey->divisibleBy === 0
                        ? $monkey->monkeyIfTrue
                        : $monkey->monkeyIfFalse;
                    $this->monkeys[$target]->catch($item);
                }
            }
        }

        rsort($this->inspections, SORT_NUMERIC);

        return $this->inspections[0] * $this->inspections[1];
    }

    protected function parse(): void
    {
        $monkeys = explode(PHP_EOL . PHP_EOL, $this->readText());
        foreach ($monkeys as $monkey) {
            $monkey = Monkey::fromString($monkey);
            $this->monkeys[$monkey->number] = $monkey;
            $this->inspections[$monkey->number] = 0;
        }
    }
}

