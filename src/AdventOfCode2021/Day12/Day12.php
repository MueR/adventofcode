<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day12;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 12 puzzle.
 *
 * @property array{int} $input
 */
class Day12 extends AbstractSolver
{
    protected array $paths = [];

    public function partOne() : int
    {
        return $this->findPaths($this->paths, 'start');
    }

    public function partTwo() : int
    {
        return $this->findPaths($this->paths, 'start', allowRevisit: true);
    }

    protected function findPaths(array $paths, string $from, array $smallCavesVisited = [], bool $allowRevisit = false): int
    {
        $result = 0;

        if ($this->isSmallCave($from)) {
            $smallCavesVisited[] = $from;
            if ($allowRevisit && count($smallCavesVisited) - count(array_unique($smallCavesVisited)) > 1) {
                return 0;
            }
            if (!$allowRevisit) {
                $paths = array_filter($paths, static fn (Path $path) => $path->to !== $from);
            }
        }

        $next = array_filter($paths, static fn (Path $path) => $path->from === $from);
        if (count($next) === 0) {
            return 0;
        }
        foreach ($next as $path) {
            if ($path->to === 'end') {
                $result++;
            } else {
                $result += $this->findPaths($paths, $path->to, $smallCavesVisited, $allowRevisit);
            }
        }

        return $result;
    }

    protected function isSmallCave(string $cave): bool
    {
        return $cave === strtolower($cave) && $cave !== 'start';
    }

    protected function parse(): void
    {
        $paths = explode("\n", $this->readText());
        foreach ($paths as $path)
        {
            [$from, $to] = explode('-', $path);
            $this->paths[] = new Path($from, $to);
            $this->paths[] = new Path($to, $from);
        }
        $this->paths = array_unique($this->paths);
        $this->paths = array_filter($this->paths, static fn (Path $path) => $path->from !== 'end' && $path->to !== 'start');
    }
}

class Path
{
    public function __construct(public string $from, public string $to)
    {
    }

    public function __toString(): string
    {
        return $this->from . '-' . $this->to;
    }
}

