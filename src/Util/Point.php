<?php

namespace MueR\AdventOfCode\Util;

use MueR\AdventOfCode\Util\Algorithm\AStar\Node\NodeIdentifierInterface;

class Point implements \Stringable, NodeIdentifierInterface
{
    public function __construct(
        public readonly int $x,
        public readonly int $y,
    ) {
    }

    public function equals(Point $point): bool
    {
        return $this->x === $point->x && $this->y === $point->y;
    }

    public function isNeighbour(Point $point): bool
    {
        return abs($this->x - $point->x) <= 1 || abs($this->y - $point->y) <= 1;
    }

    public function isTouching(Point $point): bool
    {
        return abs($this->x - $point->x) <= 1 && abs($this->y - $point->y) <= 1;
    }

    public function move(string $direction, int $amount = 1): Point
    {
        return match ($direction) {
            'N', 'U' => $this->up(),
            'S', 'D' => $this->down(),
            'W', 'L' => $this->left(),
            'E', 'R' => $this->right(),
        };
    }

    public function up(int $amount = 1): Point
    {
        return $this->translate(0, $amount);
    }

    public function down(int $amount = -1): Point
    {
        return $this->translate(0, $amount);
    }

    public function left(int $amount = -1): Point
    {
        return $this->translate($amount, 0);
    }

    public function right(int $amount = 1): Point
    {
        return $this->translate($amount, 0);
    }

    public function translate(int $x, int $y): Point
    {
        return new Point($this->x + $x, $this->y + $y);
    }

    public function getNeighbours(): array
    {
        return [
            new Point($this->x, $this->y - 1),
            new Point($this->x - 1, $this->y),
            new Point($this->x, $this->y + 1),
            new Point($this->x + 1, $this->y),
        ];
    }

    public function __toString(): string
    {
        return $this->getUniqueNodeId();
    }

    public function getUniqueNodeId(): string
    {
        return sprintf('Point<%d,%d>', $this->x, $this->y);
    }
}
