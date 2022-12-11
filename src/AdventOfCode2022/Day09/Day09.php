<?php
/**
 * Part of AdventOfCode 2022
 */


declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2022\Day09;

use Ds\Set;
use MueR\AdventOfCode\AbstractSolver;
use MueR\AdventOfCode\Util\Math;
use MueR\AdventOfCode\Util\Move;
use MueR\AdventOfCode\Util\Point;

/**
 * Day 9 puzzle.
 *
 * @see https://adventofcode.com/2022/day/9
 */
class Day09 extends AbstractSolver
{
    /** @var Point[] */
    private array $rope;
    /** @var Set<Point> */
    private Set $visitedOne;
    /** @var Set<Point> */
    private Set $visitedTwo;
    private const ROPE_LENGTH = 10;

    /**
     * Solver method for part 1 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/9
     */
    public function partOne(): int
    {
        return $this->visitedOne->count();
    }

    /**
     * Solver method for part 2 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/9#part2
     */
    public function partTwo(): int
    {
        return $this->visitedTwo->count();
    }

    protected function parse(): void
    {
        $this->rope = array_fill(0, self::ROPE_LENGTH, new Point(0, 0));
        $this->visitedOne = new Set([(string) new Point(0, 0)]);
        $this->visitedTwo = new Set([(string) new Point(0, 0)]);
        $steps = array_map(
            static fn (string $line) => Move::fromString($line),
            explode(PHP_EOL, $this->readText())
        );
        foreach ($steps as $step) {
            $this->move($step);
        }
    }

    private function move(Move $move): void
    {
        for ($step = 0; $step < $move->steps; $step++) {
            $this->rope[0] = $this->rope[0]->move($move->direction);
            for ($i = 1; $i < self::ROPE_LENGTH; $i++) {
                if (!$this->rope[$i - 1]->isTouching($this->rope[$i])) {
                    $this->rope[$i] = $this->moveKnot($this->rope[$i - 1], $this->rope[$i]);
                }
            }
            $this->visitedOne->add((string) $this->rope[1]);
            $this->visitedTwo->add((string) $this->rope[self::ROPE_LENGTH - 1]);
        }
    }

    private function moveKnot(Point $pulling, Point $pulled): Point
    {
        $x = Math::sign($pulling->x - $pulled->x);
        $y = Math::sign($pulling->y - $pulled->y);

        return $pulled->translate($x, $y);
    }
}

