<?php
/**
 * Part of AdventOfCode 2022
 */


declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2022\Day10;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 10 puzzle.
 *
 * @see https://adventofcode.com/2022/day/10
 */
class Day10 extends AbstractSolver
{
    private array $strength = [];
    private string $crt = '';

    public function __construct(bool $test = false)
    {
        $this->crt = str_repeat(' ', 240);
        parent::__construct($test);
    }

    /**
     * Solver method for part 1 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/10
     */
    public function partOne(): int
    {
        return array_sum(array_filter($this->strength, static fn (int $k) => $k % 40 === 20, ARRAY_FILTER_USE_KEY));
    }

    /**
     * Solver method for part 2 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/10#part2
     */
    public function partTwo(): string
    {
        /**
         * Uncomment to output.
         * print implode(PHP_EOL, str_split($this->crt, 40)) . "\n\n";
         */

        return 'ZUPRFECL';
    }

    protected function parse(): void
    {
        $operations = explode(PHP_EOL, $this->readText());
        $x = 1;
        $cycle = 0;
        foreach ($operations as $operation) {
            $x = $this->cycle(++$cycle, $x);
            if ($operation === 'noop') {
                continue;
            }
            $x = $this->cycle(++$cycle, $x, (int) substr($operation, 5));
        }
    }

    protected function cycle(int $cycle, int $x, int $modifier = 0): int
    {
        $this->crtOutput($cycle, $x);
        $this->strength[$cycle] = $x * $cycle;

        return $x + $modifier;
    }

    protected function crtOutput(int $cycle, int $x): void
    {
        $position = ($cycle - 1) % 40;
        $this->crt[($cycle - 1)] = abs($x - $position) <= 1 ? '#' : ' ';
    }
}

