<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day03;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 3 puzzle.
 *
 * @property array{int} $input
 */
class Day03 extends AbstractSolver
{
    public function partOne() : int
    {
        $gamma = $epsilon = '';
        for ($i = 0, $l = strlen($this->getInput(0)); $i < $l; $i++) {
            $values = [0 => 0, 1 => 0];
            foreach ($this->getInput() as $bit) {
                $values[((int)$bit[$i])]++;
            }
            $gamma .= $values[0] > $values[1] ? 0 : 1;
            $epsilon .= $values[0] < $values[1] ? 0 : 1;
        }
        return bindec($gamma) * bindec($epsilon);
    }

    public function partTwo() : int
    {
        $oxygen = $this->findValue($this->getInput(), true);
        $co2 = $this->findValue($this->input, false);

        return bindec($oxygen) * bindec($co2);
    }

    public function findValue(array $input, bool $mostCommon) : ?string
    {
        for ($i = 0, $l = strlen($input[0]); $i < $l; $i++) {
            $input = $this->reduce($input, $i, $mostCommon);
            if (count($input) === 1) {
                return array_shift($input);
            }
        }

        throw new \RuntimeException('No valid combination found.');
    }

    public function reduce(array $input, int $position, bool $mostCommon) : array
    {
        $search = $mostCommon ? '1' : '0';
        $matching = 2 * count(array_filter($input, static fn ($line) => $line[$position] === $search));
        $keepLine = $search;
        if ($matching !== count($input)) {
            $keepLine = $matching > count($input) ? '1' : '0';
        }

        return array_filter($input, static fn ($line) => $line[$position] === $keepLine);
    }
}

