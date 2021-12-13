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
         * █  █ ████   ██ █  █   ██ ███   ██    ██
         * █  █ █       █ █  █    █ █  █ █  █    █
         * ████ ███     █ ████    █ █  █ █       █
         * █  █ █       █ █  █    █ ███  █       █
         * █  █ █    █  █ █  █ █  █ █ █  █  █ █  █
         * █  █ ████  ██  █  █  ██  █  █  ██   ██
         *
         * Set below to true to print output.
         */
        // $this->getPointCount(true);

        return 'HEJHJRCJ';
    }

    protected function fold(int $at, string $axis): void
    {
        $this->paperSize[$axis] = $at - 1;
        foreach ($this->points as $point) {
            if ($point->{$axis} > $at) {
                $point->{$axis} = $at - ($point->$axis - $at);
            }
        }
    }

    protected function getPointCount(bool $printPaper = false): int
    {
        $count = 0;
        if ($printPaper) {
            $grid = array_fill(0, $this->paperSize['y'] + 1, str_repeat(' ', $this->paperSize['x'] + 1));
        }
        foreach ($this->points as $point) {
            if ($point->x > $this->paperSize['x'] && $point->y > $this->paperSize['y']) {
                continue;
            }
            $count++;
            if ($printPaper) {
                $grid[$point->y][$point->x] = '#';
            }
        }
        if ($printPaper) {
            // Just for readability. Since it's multibyte, only replace now.
            print str_replace('#', '█', implode("\n", $grid)) . "\n\n";
        }

        return $count;
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
            if (preg_match('/(\w)=([\d]+)$/', $fold, $match)) {
                $this->folds[] = ['axis' => $match[1], 'index' => (int) $match[2]];
            }
        }
    }
}

