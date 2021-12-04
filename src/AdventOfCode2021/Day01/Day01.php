<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day01;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 1 puzzle.
 *
 * @property array{int} $input
 */
class Day01 extends AbstractSolver
{
    public function partOne() : int
    {
        for ($result = 0, $i = 0, $m = count($this->getInput()) - 1; $i < $m; $i++) {
            $result += (int) ($this->getInput($i) < $this->getInput($i + 1));
        }

        return $result;
    }

    public function partTwo() : int
    {
        for ($result = 0, $i = 0, $m = count($this->getInput()) - 3; $i < $m; $i++) {
            $current = array_sum(array_slice($this->getInput(), $i, 3));
            $next = array_sum(array_slice($this->getInput(), $i + 1, 3));
            if ($current < $next) {
                $result++;
            }
        }

        return $result;
    }
}
