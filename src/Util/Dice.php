<?php

namespace MueR\AdventOfCode\Util;

class Dice
{
    private int $current = 0;
    private int $timesRolled = 0;

    public function __construct(private int $sides = 100)
    {
    }

    public function getTimesRolled(): int
    {
        return $this->timesRolled;
    }

    public function roll(): int
    {
        $this->timesRolled++;
        return random_int(1, $this->sides - 1);
    }

    public function next(): int
    {
        $this->timesRolled++;
        if ($this->current > $this->sides - 1) {
            $this->current = 0;
        }

        return ++$this->current;
    }
}
