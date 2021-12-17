<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day17;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 17 puzzle.
 *
 * @property array{int} $input
 */
class Day17 extends AbstractSolver
{
    private array $target;
    private int $best = 0;
    private array $found = [];

    public function partOne() : int
    {
        foreach (range(1, $this->target['x'][1] + 1) as $targetX) {
            foreach (range($this->target['y'][0], -$this->target['y'][1] + ($this->target['y'][1] - $this->target['y'][0])) as $targetY) {
                $this->step($targetX, $targetY);
            }
        }

        return $this->best;
    }

    public function partTwo() : int
    {
        return count($this->found);
    }

    protected function step(int $targetX, int $targetY): void
    {
        $x = $y = $height = 0;
        $velocityX = $targetX;
        $velocityY = $targetY;

        while ($velocityX > 0 || $y > $this->target['y'][0]) {
            $x += $velocityX;
            $y += $velocityY;
            $height = max($height, $y);
            $velocityX = max(0, $velocityX - 1);
            --$velocityY;
            if ($this->isInRange($x, $y)) {
                $this->best = max($this->best, $height);
                $this->found[$targetX .','. $targetY] = [$targetX, $targetY];
            }
        }
    }

    protected function isInRange(int $x, int $y): bool
    {
        return $x >= $this->target['x'][0] && $x <= $this->target['x'][1]
            && $y >= $this->target['y'][0] && $y <= $this->target['y'][1];
    }

    protected function parse(): void
    {
        preg_match('/x=(-?[\d]+)\.\.(-?[\d]+), y=(-?[\d]+)\.\.(-?[\d]+)/', $this->readText(), $target);
        array_shift($target);
        $target = array_map(static fn (string $pos) => (int) $pos, $target);
        $this->target = [
            'x' => [$target[0], $target[1]],
            'y' => [$target[2], $target[3]],
        ];

        sort($this->testResult);
    }
}

