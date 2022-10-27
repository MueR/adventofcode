<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day15;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 15 puzzle.
 *
 * @property array{int} $input
 */
class Day15 extends AbstractSolver
{
    protected Grid $grid;

    public function partOne(): int
    {
        return $this->grid->solve();
    }

    public function partTwo(): int
    {
        return $this->grid->solve(5);
    }

    protected function parse(): void
    {
        $lines = explode("\n", $this->readText());
        $this->grid = new Grid(array_map(
            static fn (string $line) => array_map(
                static fn (string $char) => (int) $char,
                str_split($line)
            ),
            $lines
        ));
    }
}
