<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day02;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 2 puzzle.
 *
 * @property array{int} $input
 */
class Day02 extends AbstractSolver
{
    /**
     * Aim is actually the depth in part one.
     */
    private int $aim = 0;

    private int $depth = 0;

    private int $position = 0;

    public function partOne() : int
    {
        $this->navigate();

        return $this->aim * $this->position;
    }

    public function partTwo() : int
    {
        return $this->depth * $this->position;
    }

    private function navigate() : void
    {
        $input = explode("\n", $this->readText());
        foreach ($input as $line) {
            [$direction, $units] = explode(' ', $line);
            $units = (int) $units;
            switch ($direction) {
                case 'forward':
                    $this->position += $units;
                    $this->depth += $this->aim * $units;
                    break;
                case 'up':
                    $this->aim -= $units;
                    break;
                case 'down':
                    $this->aim += $units;
                    break;
            }
        }
    }
}

