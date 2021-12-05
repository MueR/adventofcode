<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day05;

use MueR\AdventOfCode\AbstractSolver;
use MueR\AdventOfCode\Util\Util;
use WeakMap;
use YaLinqo\Enumerable;

/**
 * Day 5 puzzle.
 *
 * @property array{int} $input
 */
class Day05 extends AbstractSolver
{
    private array $lines = [];

    public function partOne(): int
    {
        /** @var Line[] $lines */
        $lines = array_filter($this->lines, static fn(Line $line) => $line->isHorizontal() || $line->isVertical());
        return $this->getDangerCount($lines);
    }

    public function partTwo(): int
    {
        return $this->getDangerCount($this->lines);
    }

    protected function getDangerCount(array $lines): int
    {
        $points = [];
        foreach ($lines as $line) {
            foreach ($line->pointsOnLine() as $point) {
                if (!array_key_exists((string) $point, $points)) {
                    $points[(string) $point] = 0;
                }
                $points[(string) $point]++;
            }
        }

        return count(array_filter($points, static fn($count) => $count > 1));
    }

    protected function parse(): void
    {
        $content = explode(PHP_EOL, $this->readText());
        foreach ($content as $line) {
            [$start, $end] = explode(' -> ', $line);
            $this->lines[] = new Line(Vector::fromString($start), Vector::fromString($end));
        }
    }
}

class Vector
{
    public function __construct(public int $x, public int $y)
    {
    }

    public function equals(Vector $vector): bool
    {
        return $this->x === $vector->x && $this->y === $vector->y;
    }

    public function move($x, $y): void
    {
        $this->x += $x;
        $this->y += $y;
    }

    public function __toString(): string
    {
        return $this->x . ',' . $this->y;
    }

    public static function fromString(string $string): self
    {
        [$x, $y] = explode(',', $string);

        return new static((int) $x, (int) $y);
    }
}

class Line
{
    public function __construct(public Vector $start, public Vector $end)
    {
    }

    public function isHorizontal(): bool
    {
        return $this->start->x === $this->end->x;
    }

    public function isVertical(): bool
    {
        return $this->start->y === $this->end->y;
    }

    public function pointsOnLine(): array
    {
        $points = [];
        $current = clone $this->start;
        $stepX = Util::sign($this->end->x - $this->start->x);
        $stepY = Util::sign($this->end->y - $this->start->y);

        while (!$current->equals($this->end)) {
            $points[] = clone $current;
            $current->move($stepX, $stepY);
        }
        $points[] = $this->end;

        return $points;
    }
}
