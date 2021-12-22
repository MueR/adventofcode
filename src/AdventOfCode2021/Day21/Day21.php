<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day21;

use MueR\AdventOfCode\AbstractSolver;
use SplObjectStorage;

/**
 * Day 21 puzzle.
 *
 * @property array{int} $input
 */
class Day21 extends AbstractSolver
{
    /** @var Player[] */
    private array $players = [];
    private int $die = 0;
    private int $rolls = 0;

    public function partOne(): int
    {
        $this->players = [
            new Player(1),
            new Player(10),
        ];
        while (true) {
            foreach ($this->players as $player => $scoreCard) {
                if ($this->turn($player, 1000) !== false) {
                    return $this->players[($player + 1) % count($this->players)]->score * $this->rolls;
                }
            }
        }
    }

    public function partTwo(): int
    {
        $firstGame = new Game(1, 0, 10, 0, true);
        return -1;
    }

    protected function turn(int $player, int $winScore): bool|int
    {
        for ($i = 0; $i < 3; $i++) {
            $roll = $this->roll();
            $this->players[$player]->position += $roll;
        }
        $this->players[$player]->position = 1 + (($this->players[$player]->position - 1) % 10);
        $this->players[$player]->score += $this->players[$player]->position;

        return $this->players[$player]->score >= $winScore;
    }

    protected function roll(): int
    {
        $this->rolls++;
        if (++$this->die > 100) {
            $this->die = 1;
        }

        return $this->die;
    }
}

class Player
{
    public function __construct(public int $position, public int $score = 0)
    {
    }
}

class Game
{
    public function __construct(public int $p1Position, public int $p1Score, public int $p2Position, public int $p2Score, public bool $p1Playing)
    {
    }
}
