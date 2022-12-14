<?php

namespace MueR\AdventOfCode\Util\Algorithm;

class Dijkstra
{
    /**
     * @param array<string, array<string, int>> $graph [node][neighbour] = cost
     * @param string $start
     * @param array $endNodes
     *
     * @return array<string, int> [node] distance
     */
    public function calculateDistance(array $graph, string $start, array $endNodes = []): array
    {
        $unvisited = array_keys($graph);
        $distance = array_fill_keys($unvisited, PHP_INT_MAX);
        $distance[$start] = 0;

        do {
            $currentNode = $this->minDistanceNode($distance, $unvisited);
            unset($unvisited[array_search($currentNode, $unvisited, true)]);

            foreach ($graph[$currentNode] as $neighbour => $cost) {
                $distance[$neighbour] = $distance[$currentNode] + $cost;
            }
        } while (!in_array($currentNode, $endNodes, true));

        return $distance;
    }

    private function minDistanceNode(array $distance, array $unvisited): string
    {
        $min = PHP_INT_MAX;
        $next = null;

        foreach ($distance as $nodeName => $currentDistance) {
            if ($currentDistance <= $min && in_array($nodeName, $unvisited, true)) {
                $min = $currentDistance;
                $next = $nodeName;
            }
        }

        return $next;
    }
}
