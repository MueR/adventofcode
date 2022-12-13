<?php

namespace MueR\AdventOfCode\AdventOfCode2022\Day12;

class Terrain
{
    public const INFINITE = PHP_INT_MAX;

    public readonly int $height;
    public readonly int $width;

    public function __construct(private readonly array $terrainCost)
    {
        $this->height = count($this->terrainCost);
        $this->width = count($this->terrainCost[0]);
    }

    public function exists(int $y, int $x): bool
    {
        return isset($this->terrainCost[$y][$x]);
    }

    public function getCost(int $y, int $x): int
    {
        if (!$this->exists($y, $x)) {
            throw new \InvalidArgumentException("Invalid tile: <$y,$x>");
        }

        return $this->terrainCost[$y][$x];
    }

    public function printTerrain(): void
    {
        foreach ($this->terrainCost as $row) {
            foreach ($row as $col) {
                printf("%2d", $col);
            }
            print "\n";
        }
        print "\n";
    }
}
