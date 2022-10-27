<?php

namespace MueR\AdventOfCode\AdventOfCode2021\Day21;

use JetBrains\PhpStorm\Pure;

class Player
{
    public function __construct(public int $position, public int $score = 0)
    {
    }

    #[Pure]
    public function move(int $amount): Player
    {
        $newPosition = $this->position + $amount;
        $newPosition = 1 + (($newPosition - 1) % 10);

        return new Player($newPosition, $this->score + $newPosition);
    }

    public function __toString(): string
    {
        return sprintf('%d_%d', $this->position, $this->score);
    }
}
