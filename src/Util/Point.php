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

    public function equals(self $point): bool
    {
        return $this->x === $point->x && $this->y === $point->y;
    }

    public function isNeighbour(self $point): bool
    {
        return abs($this->x - $point->x) <= 1 || abs($this->y - $point->y) <= 1;
    }

    public function isTouching(self $point): bool
    {
        return abs($this->x - $point->x) <= 1 && abs($this->y - $point->y) <= 1;
    }

    public function move(string $direction, int $amount = 1): self
    {
        return match ($direction) {
            'N', 'U' => $this->up(),
            'S', 'D' => $this->down(),
            'W', 'L' => $this->left(),
            'E', 'R' => $this->right(),
        };
    }

    public function up(int $amount = 1): self
    {
        return $this->translate(0, $amount);
    }

    public function down(int $amount = -1): self
    {
        return $this->translate(0, $amount);
    }

    public function left(int $amount = -1): self
    {
        return $this->translate($amount, 0);
    }

    public function right(int $amount = 1): self
    {
        return $this->translate($amount, 0);
    }

    public function translate(int $x, int $y): self
    {
        return new Point($this->x + $x, $this->y + $y);
    }

    /**
     * @return Point[]
     */
    public function getNeighbours(?array $grid = null): array
    {
        if ($grid) {
            // Since PHP makes unique references, allow the use of the original values.
            $neighbours = [
                $grid[$this->y - 1][$this->x] ?? null,
                $grid[$this->y + 1][$this->x] ?? null,
                $grid[$this->y][$this->x - 1] ?? null,
                $grid[$this->y][$this->x + 1] ?? null,
            ];
        } else {
            $neighbours = [
                new Point($this->x, $this->y - 1),
                new Point($this->x - 1, $this->y),
                new Point($this->x, $this->y + 1),
                new Point($this->x + 1, $this->y),
            ];
        }

        return array_filter($neighbours);
    }

    /**
     * @return self[]
     */
    public function lineTo(self $point): array
    {
        if ($this->x === $point->x) {
            return array_map(fn (int $y) => new self($this->x, $y), range($this->y, $point->y));
        }
        if ($this->y === $point->y) {
            return array_map(fn (int $x) => new self($x, $this->y), range($this->x, $point->x));
        }

        throw new \InvalidArgumentException('Points are not on the same horizontal or vertical line.');
    }

    public function manhattanTo(Point $p): int
    {
        return abs($p->x - $this->x) + abs($p->y - $this->y);
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
