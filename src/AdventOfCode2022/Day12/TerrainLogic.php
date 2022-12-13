<?php

namespace MueR\AdventOfCode\AdventOfCode2022\Day12;

use MueR\AdventOfCode\Util\Algorithm\AStar\DomainLogicInterface;
use MueR\AdventOfCode\Util\Point;

class TerrainLogic implements DomainLogicInterface
{
    protected array $points;

    public function __construct(protected readonly Terrain $terrainCost)
    {
        $this->generatePoints();
    }

    /**
     * @param Point $node
     */
    public function getAdjacentNodes(mixed $node): iterable
    {
        $currentNodeCost = $this->terrainCost->getCost($node->y, $node->x);
        foreach ($node->getNeighbours() as $point) {
            if (!isset($this->points[$point->y][$point->x])) {
                continue;
            }
            $nodeCost = $this->terrainCost->getCost($point->y, $point->x);
            if (abs($nodeCost - $currentNodeCost) <= 1) {
                yield $point;
            }
        }
    }

    /**
     * @param Point $fromNode
     * @param Point $toNode
     */
    public function calculateEstimatedCost(mixed $fromNode, mixed $toNode): float|int
    {
        return $this->euclideanDistance($fromNode, $toNode);
    }

    /**
     * @param Point $node
     * @param Point $adjacent
     */
    public function calculateRealCost(mixed $node, mixed $adjacent): float|int
    {
        if ($node->isNeighbour($adjacent)) {
            return $this->terrainCost->getCost($adjacent->y, $adjacent->x);
        }

        return Terrain::INFINITE;
    }

    protected function euclideanDistance(Point $a, Point $b): float
    {
        $xFactor = ($a->y - $b->y) ** 2;
        $yFactor = ($a->x - $b->x) ** 2;

        return sqrt($xFactor + $yFactor);
    }

    private function generatePoints(): void
    {
        for ($y = 0; $y < $this->terrainCost->height; $y++) {
            for ($x = 0; $x < $this->terrainCost->width; $x++) {
                $this->points[$y][$x] = new Point($x, $y);
            }
        }
    }

    public function getPoint(int $y, int $x)
    {
        return $this->points[$y][$x];
    }

    public function getAdjacentBoundaries(Point $p): array
    {
        return [
            max(0, $p->y - 1),
            max($this->terrainCost->height, $p->y + 1),
            max(0, $p->x - 1),
            max($this->terrainCost->width, $p->x + 1),
        ];
    }
}
