<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day09;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 9 puzzle.
 *
 * @property array{int} $input
 */
class Day09 extends AbstractSolver
{
    public array $points = [];
    /** @var Position[] */
    protected array $lowestPoints = [];

    public function __construct(bool $test = false)
    {
        parent::__construct($test);
        // Required if XDebug is enabled.
        ini_set('xdebug.max_nesting_level', '1024');
    }

    public function partOne() : int
    {
        $lowPoints = 0;
        foreach ($this->points as $points) {
            foreach ($points as $position) {
                if ($position->isLowest($position->getNeighbours($this->points), $position->value)) {
                    $this->lowestPoints[] = $position;
                    $lowPoints += $position->value + 1;
                }
            }
        }

        return $lowPoints;
    }

    public function partTwo() : int
    {
        $result = [];
        foreach ($this->lowestPoints as $position) {
            $result[] = $position->findBasin($this->points);
        }
        sort($result, SORT_DESC);

        return array_product(array_slice($result, -3));
    }

    protected function parse(): void
    {
        $lines = explode("\n", $this->readText());
        $this->points = [];
        foreach ($lines as $row => $line) {
            foreach (str_split($line) as $col => $value) {
                $this->points[$row][$col] = new Position($row, $col, (int) $value);
            }
        }
    }
}
