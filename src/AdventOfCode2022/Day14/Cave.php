<?php

namespace MueR\AdventOfCode\AdventOfCode2022\Day14;

use Illuminate\Support\Collection;
use MueR\AdventOfCode\Util\Point;

class Cave
{
    private array $cave;
    private int $bedrock;

    public function __construct(string $input)
    {
        $rocks = (new Collection(explode(PHP_EOL, $input)))
            ->map(fn (string $line) => explode(' -> ', $line))
            ->map(fn (array $points) => array_map(
                null,
                array_slice($points, 0, count($points) - 1),
                array_slice($points, 1)
            ))
            ->flatMap(function (array $lengths) {
                return (new Collection($lengths))->flatMap(fn (array $pair) => $this->toLine($pair));
            })
            ->unique(fn (Point $p) => (string) $p)
            ->values()
        ;

        $this->bedrock = $rocks
            ->sort(fn (Point $a, Point $b) => ($a->y > $b->y) - ($a->y < $b->y))
            ->last()
            ->y + 2;


        $this->cave = $rocks->reduce(function (array $carry, Point $rock) {
            $carry[$rock->x][$rock->y] = 'R';

            return $carry;
        }, []);
    }

    public function addSand(array $sand): void
    {
        $this->cave[$sand[0]][$sand[1]] = 'S';
    }

    public function moveSand(array $start): array|false
    {
        $down = [$start[0], $start[1] + 1];
        if (!isset($this->cave[$down[0]][$down[1]])) {
            if ($start[1] === $this->bedrock) {
                return false;
            }

            return $this->moveSand($down);
        }

        $downLeft = [$start[0] - 1, $start[1] + 1];
        if (!isset($this->cave[$downLeft[0]][$downLeft[1]])) {
            if ($start[1] === $this->bedrock) {
                return false;
            }

            return $this->moveSand($downLeft);
        }

        $downRight = [$start[0] + 1, $start[1] + 1];
        if (!isset($this->cave[$downRight[0]][$downRight[1]])) {
            if ($start[1] === $this->bedrock) {
                return false;
            }

            return $this->moveSand($downRight);
        }

        return $start;
    }

    public function moveSandWithFloor(array $start): array
    {
        if ($start[1] + 1 === $this->bedrock) {
            return $start;
        }

        $down = [$start[0], $start[1] + 1];
        if (!isset($this->cave[$down[0]][$down[1]])) {
            return $this->moveSandWithFloor($down);
        }

        $downLeft = [$start[0] - 1, $start[1] + 1];
        if (!isset($this->cave[$downLeft[0]][$downLeft[1]])) {
            return $this->moveSandWithFloor($downLeft);
        }

        $downRight = [$start[0] + 1, $start[1] + 1];
        if (!isset($this->cave[$downRight[0]][$downRight[1]])) {
            return $this->moveSandWithFloor($downRight);
        }

        return $start;
    }

    public function getSandCount(): int
    {
        return (new Collection($this->cave))
            ->map(fn (array $row) => (new Collection($row))->filter(fn ($value) => $value === 'S')->count())
            ->sum()
        ;
    }

    /**
     * @return Point[]
     */
    private function toLine(array $pair): array
    {
        $start = new Point(...array_map('intval', explode(',', $pair[0])));
        $end = new Point(...array_map('intval', explode(',', $pair[1])));

        return $start->lineTo($end);
    }
}
