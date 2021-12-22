<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day21;

use JetBrains\PhpStorm\Pure;
use MueR\AdventOfCode\AbstractSolver;
use MueR\AdventOfCode\Util\Collection\Tuple;
use MueR\AdventOfCode\Util\Dice;

/**
 * Day 21 puzzle.
 *
 * @property array{int} $input
 */
class Day21 extends AbstractSolver
{
    private array $possibleRolls = [];
    private array $universes = [];

    public function partOne(): int
    {
        $playing = new Player($this->test ? 4 : 1);
        $waiting = new Player($this->test ? 8 : 10);
        $die = new Dice();
        while (true) {
            $moved = $playing->move($die->next() + $die->next() + $die->next());
            if ($moved->score >= 1000) {
                return $waiting->score * $die->getTimesRolled();
            }
            $playing = $waiting;
            $waiting = $moved;
        }
    }

    public function partTwo(): int|float
    {
        $p1 = new Player($this->test ? 4 : 1);
        $p2 = new Player($this->test ? 8 : 10);
        $diceRolls = [1, 2, 3];
        foreach ($diceRolls as $d1) {
            foreach ($diceRolls as $d2) {
                foreach ($diceRolls as $d3) {
                    $this->possibleRolls[$d1 + $d2 + $d3] = ($this->possibleRolls[$d1 + $d2 + $d3] ?? 0) + 1;
                }
            }
        }

        $result = $this->split($p1, $p2);

        return max($result->getLeft(), $result->getRight());
    }

    protected function split(Player $p1, Player $p2): Tuple
    {
        if (isset($this->universes[(string)$p1][(string)$p2])) {
            return $this->universes[(string)$p1][(string)$p2];
        }
        $result = new Tuple();
        for ($roll = 3; $roll < 10; $roll++) {
            $played = $p1->move($roll);
            if ($played->score >= 21) {
                $result = $result->incrementLeft($this->possibleRolls[$roll]);
            } else {
                $result = $result->add($this->split($p2, $played)->flip()->times($this->possibleRolls[$roll]));
            }
        }
        $this->universes[(string)$p1][(string)$p2] = $result;

        return $result;
    }
}

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
