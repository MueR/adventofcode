<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day01;

use JetBrains\PhpStorm\Pure;
use MueR\AdventOfCode\AbstractSolver;

class Day01 extends AbstractSolver
{
    public function __construct()
    {
        parent::__construct();

        $this->readTextInput();
    }

    #[Pure] public function partOne(): int
    {
        $result = 0;
        for ($i = 0, $m = count($this->input) - 1; $i < $m; $i++) {
            $result += $this->input[$i] < $this->input[$i + 1] ? 1 : 0;
        }

        return $result;
    }

    #[Pure] public function partTwo(): int
    {
        $result = 0;
        for ($i = 0, $m = count($this->input) - 3; $i < $m; $i++) {
            $one = array_sum(array_slice($this->input, $i, 3));
            $two = array_sum(array_slice($this->input, $i + 1, 3));
            if ($one < $two) {
                $result++;
            }
        }

        return $result;
    }
}
