<?php
/**
 * Part of AdventOfCode 2022
 */


declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2022\Day12;

use Ds\Map;
use Ds\PriorityQueue;
use Ds\Queue;
use Ds\Set;
use MueR\AdventOfCode\AbstractSolver;
use MueR\AdventOfCode\Util\Algorithm\AStar\AStar;
use MueR\AdventOfCode\Util\Algorithm\AStar\Node\Collection\NodeHashTable;
use MueR\AdventOfCode\Util\Algorithm\AStar\Node\Node;
use MueR\AdventOfCode\Util\Point;

/**
 * Day 12 puzzle.
 *
 * @see https://adventofcode.com/2022/day/12
 */
class Day12 extends AbstractSolver
{
    private Point $start;
    private Point $end;
    private array $map;
    private array $reversed;
    private Terrain $terrain;
    private TerrainLogic $terrainLogic;

    /**
     * Solver method for part 1 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/12
     */
    public function partOne(): int
    {
        $terrain = new Terrain($this->map);
        // $terrain->printTerrain();
        $terrainLogic = new TerrainLogic($terrain);
        $aStar = new AStar($terrainLogic);
        return count($aStar->run($this->start, $this->end)) - 1;
    }

    /**
     * Solver method for part 2 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/12#part2
     */
    public function partTwo(): int
    {
        $results = [];
        $terrain = new Terrain($this->reversed);
        // $terrain->printTerrain();
        $terrainLogic = new TerrainLogic($terrain);
        $aStar = new AStar($terrainLogic);
        foreach ($this->reversed as $y => $row) {
            foreach ($row as $x => $height) {
                if ($height === 26) {
                    $run = $aStar->run($this->end, $terrainLogic->getPoint($y, $x));
                    if (!empty($run)) {
                        $results[] = count($run);
                    }
                }
            }
        }
        return empty($results) ? 0 : min($results) - 1;
        foreach ($r as $point) {
            if ($this->terrain->getCost($point->y,  $point->x) === 26) {
                $c--;
            }
        }

        return $c;
        print "Start is $this->end\n";
        $this->terrain->printTerrain();
        foreach ($this->map as $y => $row) {
            foreach ($row as $x => $height) {
                if ($height > 1) {
                    continue;
                }
                $point = new Point($x, $y);
                $r = count($aStar->run($this->end, $point));
                $results[(string)$point] = $r;
            }
        }

        return min($results) - 1;
    }

    protected function parse(): void
    {
        $base = ord('a') - 1;
        $this->map = [];
        foreach (explode(PHP_EOL, $this->readText()) as $y => $line) {
            $this->map[$y] = [];
            foreach (str_split($line) as $x => $char) {
                $value = match ($char) {
                    'S' => 0,
                    'E' => 27,
                    default => ord($char) - $base,
                };
                $this->map[$y][$x] = $value;
                $this->reversed[$y][$x] = max(abs($value - 27), 0);
                $point = new Point($x, $y);
                if ($char === 'S') {
                    $this->start = $point;
                }
                if ($char === 'E') {
                    $this->end = $point;
                }
            }
        }
    }
}

