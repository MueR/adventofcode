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
    protected ?Cave $end = null;
    /** @var Cave[] */
    protected array $caves = [];
    protected array $pathsFound = [];
    private bool $canVisitSmallTwice = false;

    public function partOne(): int
    {
        return $this->findPaths($this->start, []);
    }

    public function partTwo(): int
    {
        $this->canVisitSmallTwice = true;
        return $this->findPaths($this->start, []);
    }

    protected function findPaths(Cave $currentCave, array $previous, bool $smallCaveRevisitedOnce = false): int
    {
        if ($currentCave->end) {
            return 1;
        }

        $caveAlreadyInPath = in_array($currentCave->name, $previous, true);
        if ($currentCave->small && $caveAlreadyInPath !== false) {
            if (!$this->canVisitSmallTwice || $smallCaveRevisitedOnce || $currentCave->start) {
                return 0;
            }
            $smallCaveRevisitedOnce = true;
        }

        $previous[] = $currentCave->name;
        $result = 0;
        foreach ($currentCave->linksTo as $nextCave) {
            $result += $this->findPaths($nextCave, $previous, $smallCaveRevisitedOnce);
        }
        array_pop($previous);

        return $result;
    }

    protected function parse(): void
    {
        $paths = explode("\n", $this->readText());
        foreach ($paths as $path) {
            [$from, $to] = explode('-', $path);
            $this->addCave($from);
            $this->addCave($to);

            $this->caves[$from]->link($this->caves[$to]);
            $this->caves[$to]->link($this->caves[$from]);
        }
    }

    protected function addCave(string $name): void
    {
        if (array_key_exists($name, $this->caves)) {
            return;
        }
        $this->caves[$name] = new Cave($name);
        if (!$this->start && $this->caves[$name]->start) {
            $this->start = $this->caves[$name];
        }
        if (!$this->end && $this->caves[$name]->end) {
            $this->end = $this->caves[$name];
        }
    }
}

class Cave
{
    public bool $start = false;
    public bool $end = false;
    public bool $small = false;
    /** @var Cave[] */
    public array $linksTo = [];

    public function __construct(public string $name)
    {
        $this->start = $this->name === 'start';
        $this->end = $this->name === 'end';
        $this->small = !$this->start && !$this->end && $this->name === strtolower($this->name);
    }

    public function link(Cave $cave)
    {
        if ($cave->start) {
            return;
        }
        $this->linksTo[] = $cave;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}

