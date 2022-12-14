<?php
/**
 * Part of AdventOfCode 2022
 */


declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2022\Day12;

use MueR\AdventOfCode\AbstractSolver;
use MueR\AdventOfCode\Util\Algorithm\AStar\AStar;
use MueR\AdventOfCode\Util\Algorithm\BreadthFirstSearch;
use MueR\AdventOfCode\Util\Algorithm\Dijkstra;
use MueR\AdventOfCode\Util\Point;

/**
 * Day 12 puzzle.
 *
 * @see https://adventofcode.com/2022/day/12
 */
class Day12 extends AbstractSolver
{
    private int $costOne = 0;
    private int $costTwo = 0;

    /**
     * Solver method for part 1 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/12
     */
    public function partOne(): int
    {
        return $this->costOne;
    }

    /**
     * Solver method for part 2 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/12#part2
     */
    public function partTwo(): int
    {
        return $this->costTwo;
    }

    protected function parse(): void
    {
        $base = ord('a') - 1;
        $this->map = [];
        $lowestPoints = [];
        foreach (explode(PHP_EOL, $this->readText()) as $y => $line) {
            $this->map[$y] = [];
            foreach (str_split($line) as $x => $char) {
                $value = match ($char) {
                    'S' => 0,
                    'E' => 27,
                    default => ord($char) - $base,
                };
                $point = new ElevatedPoint($x, $y, $value);
                $this->map[$y][$x] = $point;
                if ($char === 'S') {
                    $start = (string) $point;
                }
                if ($char === 'E') {
                    $end = (string) $point;
                }
                if ($value <= 1) {
                    $lowestPoints[] = (string) $point;
                }
            }
        }

        $bfs = new BreadthFirstSearch();
        $graph = $this->buildGraph($this->map);
        $this->costOne = count($bfs->getPath($graph, $end, [$start])) - 1;
        $this->costTwo = count($bfs->getPath($graph, $end, $lowestPoints)) - 1;
    }

    /**
     * @param ElevatedPoint[][] $grid
     */
    private function buildGraph(array $grid): array
    {
        $graph = [];

        foreach ($grid as $rows) {
            foreach ($rows as $point) {
                foreach ($point->getNeighbours($grid) as $neighbour) {
                    $graph[(string) $point][] = (string) $neighbour;
                }
            }
        }

        return $graph;
    }
}

