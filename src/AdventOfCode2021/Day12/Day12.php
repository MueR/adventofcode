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
    protected ?Cave $start = null;
    private bool $canVisitSmallTwice = false;

    public function partOne(): int
    {
        return $this->findPaths($this->start);
    }

    public function partTwo(): int
    {
        $this->canVisitSmallTwice = true;
        return $this->findPaths($this->start);
    }

    protected function findPaths(Cave $currentCave, ?Cave $visitedTwice = null): int
    {
        if (!$currentCave->big && $currentCave->visited) {
            if (!$this->canVisitSmallTwice || $visitedTwice) {
                return 0;
            }
            $visitedTwice = $currentCave;
        }

        $currentCave->visited = true;

        $result = 0;
        foreach ($currentCave->linksTo as $nextCave) {
            $result += $nextCave->end ? 1 : $this->findPaths($nextCave, $visitedTwice);
        }

        if ($currentCave !== $visitedTwice) {
            $currentCave->visited = false;
        }

        return $result;
    }

    protected function parse(): void
    {
        $paths = explode("\n", $this->readText());
        $caves = [];
        foreach ($paths as $path) {
            [$from, $to] = explode('-', $path);
            if (!array_key_exists($from, $caves)) {
                $caves[$from] = new Cave($from);
            }
            if (!array_key_exists($to, $caves)) {
                $caves[$to] = new Cave($to);
            }

            $caves[$from]->link($caves[$to]);
            $caves[$to]->link($caves[$from]);
        }

        $this->start = $caves['start'];
    }
}

