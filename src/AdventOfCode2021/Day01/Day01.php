<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day01;

use JetBrains\PhpStorm\Pure;
use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 1 puzzle.
 *
 * @property array{int} $input
 */
class Day01 extends AbstractSolver
{
    #[Pure]
    public function partOne(): int
    {
        for ($result = 0, $i = 0, $m = count($this->input) - 1; $i < $m; $i++) {
            $result += (int) ($this->input[$i] < $this->input[$i + 1]);
        }

        return $result;
    }

    #[Pure]
    public function partTwo(): int
    {
        for ($result = 0, $i = 0, $m = count($this->input) - 3; $i < $m; $i++) {
            $current = array_sum(array_slice($this->input, $i, 3));
            $next = array_sum(array_slice($this->input, $i + 1, 3));
            if ($current < $next) {
                $result++;
            }
        }

        return $result;
    }
}
