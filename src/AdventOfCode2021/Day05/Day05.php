<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day05;

use MueR\AdventOfCode\AbstractSolver;
use MueR\AdventOfCode\Util\Line;
use MueR\AdventOfCode\Util\Vector;

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
