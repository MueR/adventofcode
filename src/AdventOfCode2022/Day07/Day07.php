<?php
/**
 * Part of AdventOfCode 2022
 */


declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2022\Day07;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 7 puzzle.
 *
 * @see https://adventofcode.com/2022/day/7
 */
class Day07 extends AbstractSolver
{
    private Directory $root;
    private array $flatFs = [];

    /**
     * Solver method for part 1 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/7
     */
    public function partOne() : int
    {
        return array_sum(array_filter($this->flatFs, static fn (int $dir) => $dir <= 100000));
    }

    /**
     * Solver method for part 2 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/7#part2
     */
    public function partTwo() : int
    {
        $required = 30000000 - (70000000 - $this->root->getCumulativeSize());
        $dirs = array_filter($this->flatFs, static fn (int $dir) => $dir >= $required);
        sort($dirs, SORT_NUMERIC);

        return array_shift($dirs);
    }

    protected function parse(): void
    {
        $input = explode('$ ', $this->readText());
        $currentDir = null;
        $this->root = new Directory('');
        foreach ($input as $section) {
            if ($section === '') {
                continue;
            }
            $data = explode(PHP_EOL, trim($section));
            $command = array_shift($data);

            switch (substr($command, 0, 2)) {
                case 'cd':
                    $dir = substr($command, 3);
                    switch ($dir) {
                        case '/':
                            $currentDir = $this->root;
                            break;
                        case '..':
                            $currentDir = $currentDir->getParent() ?? $this->root;
                            break;
                        default:
                            $currentDir = $currentDir->getChild($dir);
                            break;
                    }
                    break;
                case 'ls':
                    $currentDir->parseDir($data);
                    break;
            }
        }
        $this->root->flatten($this->flatFs);
    }
}

