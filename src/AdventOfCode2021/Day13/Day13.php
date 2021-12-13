<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day13;

use MueR\AdventOfCode\AbstractSolver;
use MueR\AdventOfCode\Util\Vector;
use SplObjectStorage;

/**
 * Day 13 puzzle.
 *
 * @property array{int} $input
 */
class Day13 extends AbstractSolver
{
    /**
     * @var SplObjectStorage<Vector>
     */
    protected SplObjectStorage $points;
    protected array $folds = [];
    protected array $paperSize = ['x' => 0, 'y' => 0];

    public function partOne() : int
    {
        $fold = array_shift($this->folds);
        $this->fold($fold['index'], $fold['axis']);
        return $this->getPointCount();
    }

    public function partTwo() : string
    {
        foreach ($this->folds as $fold) {
            $this->fold($fold['index'], $fold['axis']);
        }
        /*
         * Output:
         * #  # ####   ## #  #   ## ###   ##    ##
         * #  # #       # #  #    # #  # #  #    #
         * #### ###     # ####    # #  # #       #
         * #  # #       # #  #    # ###  #       #
         * #  # #    #  # #  # #  # # #  #  # #  #
         * #  # ####  ##  #  #  ##  #  #  ##   ##
         *
         * Set below to true to print output.
         */
        $this->getPointCount(false);

        return 'HEJHJRCJ';
    }

    protected function fold(int $at, string $axis): void
    {
        foreach ($this->points as $point) {
            if ($point->{$axis} > $at) {
                $point->{$axis} = $at - ($point->$axis - $at);
            }
        }
        $this->paperSize[$axis] = $at - 1;
    }

    protected function getPointCount(bool $printPaper = false): int
    {
        $grid = array_fill(0, $this->paperSize['y'] + 1, str_repeat(' ', $this->paperSize['x'] + 1));
        foreach ($this->points as $point) {
            $grid[$point->y][$point->x] = '#';
        }
        if ($printPaper) {
            printf("X: %d Y: %d\n%s\n\n", $this->paperSize['x'], $this->paperSize['y'], implode("\n", $grid));
        }

        return array_sum(array_map(fn (string $line) => substr_count($line, '#'), $grid));
    }

    protected function walk(callable $callable): mixed
    {
        $return = [];
        foreach ($this->points as $x => $row) {
            foreach ($row as $y => $point) {
                $return[] = $callable($point);
            }
        }

        return array_filter($return);
    }

    protected function parse(): void
    {
        [$points, $folds] = explode("\n\n", $this->readText());

        $this->points = new SplObjectStorage();
        foreach (explode("\n", $points) as $point) {
            [$x, $y] = explode(',', $point);
            $vector = new Vector((int) $x, (int) $y);
            $this->points->attach($vector);

            $this->paperSize['x'] = max((int) $x, $this->paperSize['x']);
            $this->paperSize['y'] = max((int) $y, $this->paperSize['y']);
        }

        foreach (explode("\n", $folds) as $fold) {
            if (preg_match('/(x|y)=([\d]+)$/', $fold, $match)) {
                $this->folds[] = ['axis' => $match[1], 'index' => (int) $match[2]];
            }
        }
    }
}

