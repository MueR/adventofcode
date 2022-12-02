<?php
/**
 * Part of AdventOfCode 2022
 */


declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2022\Day02;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 2 puzzle.
 *
 * @property array{int} $input
 * @see https://adventofcode.com/2022/day/2
 */
class Day02 extends AbstractSolver
{
    private array $turns = [];

    /**
     * Solver method for part 1 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/2
     */
    public function partOne(): int
    {
        $score = 0;
        $win = ['X' => 'C', 'Y' => 'A', 'Z' => 'B'];
        $loss = ['X' => 'B', 'Y' => 'C', 'Z' => 'A'];
        foreach ($this->turns as [$elf, $you]) {
            $score += $this->score($you);
            if ($win[$you] === $elf) {
                $score += 6;
            } elseif ($loss[$you] === $elf) {
                // No score
            } else {
                $score += 3;
            }
        }
        return $score;
    }

    /**
     * Solver method for part 2 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/2#part2
     */
    public function partTwo(): int
    {
        $score = 0;
        $win = ['A' => 'B', 'B' => 'C', 'C' => 'A'];
        $loss = ['A' => 'C', 'B' => 'A', 'C' => 'B'];
        foreach ($this->turns as [$elf, $outcome]) {
            $score += $this->play($elf, match ($outcome) {
                'X' => $loss[$elf],
                'Y' => $elf,
                'Z' => $win[$elf],
            });
        }
        return $score;
    }

    protected function parse(): void
    {
        $this->turns = array_map(fn ($line) => explode(' ', $line), explode("\n", $this->readText()));
    }

    private function play(string $elf, string $you): int
    {
        $score = $this->score($you);
        if ($you === $elf) {
            return $score + 3;
        }
        $win = ['A' => 'C', 'B' => 'A', 'C' => 'B'];

        return $score + ($win[$you] === $elf ? 6 : 0);
    }

    private function score(string $shape): int
    {
        return match ($shape) {
            'A', 'X' => 1,
            'B', 'Y' => 2,
            'C', 'Z' => 3,
            default => 0,
        };
    }
}
