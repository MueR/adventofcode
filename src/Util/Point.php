<?php

namespace MueR\AdventOfCode\Util;

class Point implements \Stringable
{
    public function __construct(public readonly int $x, public readonly int $y)
    {
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

    public function __toString(): string
    {
        return sprintf('Point<%d,%d>', $this->x, $this->y);
    }
}
