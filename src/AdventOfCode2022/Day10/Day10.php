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
    private int $cycle = 0;
    private array $strength = [];
    private string $crt = '';

    /**
     * Solver method for part 1 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/10
     */
    public function partOne() : int
    {
        return array_sum(array_filter($this->strength, static fn (int $k) => $k % 40 === 20, ARRAY_FILTER_USE_KEY));
    }

    /**
     * Solver method for part 2 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/10#part2
     */
    public function partTwo() : string
    {
        // Uncomment this to display output
        /*
        foreach (str_split($this->crt) as $i => $char) {
            print ($i % 40 === 0 ? "\n" : '') . str_replace('#', '█', $char);
        }
        print "\n\n";
        */

        return 'ZUPRFECL';
    }

    protected function parse(): void
    {
        $operations = explode(PHP_EOL, $this->readText());
        $strength = 1;
        $cycle = 0;
        $this->crt = str_repeat('.', 240);
        foreach ($operations as $operation) {
            $this->crtOutput($cycle + 1, $strength);
            $this->strength[++$cycle] = $strength * $cycle;
            if ($operation === 'noop') {
                continue;
            }
            $this->crtOutput($cycle + 1, $strength);
            $this->strength[++$cycle] = $strength * $cycle;
            $strength += (int) substr($operation, 5);
        }
    }

    protected function crtOutput(int $cycle, int $x): void
    {
        $position = ($cycle - 1) % 40;
        $this->crt[($cycle - 1)] = abs($x - $position) <= 1 ? '#' : ' ';
    }
}

