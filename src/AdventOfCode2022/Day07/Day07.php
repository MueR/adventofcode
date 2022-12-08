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
        $this->root = $currentDir = new Directory('');
        foreach ($input as $section) {
            if ($section === '') {
                continue;
            }
            $data = explode(PHP_EOL, trim($section));
            $command = array_shift($data);

            if (str_starts_with($command, 'cd')) {
                $dir = substr($command, 3);
                $currentDir = match ($dir) {
                    '/' => $this->root,
                    '..' => $currentDir->getParent() ?? $this->root,
                    default => $currentDir->getChild($dir),
                };
                continue;
            }

            $currentDir->parseDir($data);
        }
        $this->root->flatten($this->flatFs);
    }
}

