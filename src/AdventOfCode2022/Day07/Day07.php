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

class Directory
{
    private string $fullPath;
    private int $cumulativeSize = 0;
    private array $files = [];
    /** @var Directory[] */
    private array $children = [];
    private int $size = 0;
    private int $depth = 0;

    public function __construct(
        private string $name,
        private ?self $parent = null,
    ) {
        $this->fullPath = $this->name . '/';
        if ($this->parent) {
            $this->fullPath = $this->parent->getFullPath() . $this->fullPath;
            $this->depth = $this->parent->getDepth() + 1;
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getFullPath(): string
    {
        return $this->fullPath;
    }

    public function getParent(): ?Directory
    {
        return $this->parent;
    }

    public function getChildren(): array
    {
        return $this->children;
    }

    public function getChild(string $name): Directory
    {
        return $this->children[$name];
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getCumulativeSize(): int
    {
        return $this->cumulativeSize;
    }

    public function getFiles(): array
    {
        return $this->files;
    }

    public function getDepth(): int
    {
        return $this->depth;
    }

    public function parseDir(array $content): void
    {
        foreach ($content as $line) {
            [$size, $name] = explode(' ', $line);
            if ($size === 'dir') {
                $dir = new Directory($name, $this);
                $this->children[$name] = $dir;
                continue;
            }
            $this->files[$name] = ['name' => $name, 'size' => $size];
            $this->size += (int) $size;
        }
        $this->addToCumulativeSize($this->size);
    }

    public function addToCumulativeSize(int $size): void
    {
        $this->cumulativeSize += $size;
        $this->getParent()?->addToCumulativeSize($size);
    }

    public function flatten(array &$flat = []): void
    {
        $flat[$this->getFullPath()] = $this->getCumulativeSize();
        foreach ($this->children as $child) {
            $child->flatten($flat);
        }
    }

    public function __toString()
    {
        return sprintf("[DIR] %-20s %-20s %s\n", $this->getCumulativeSize(), $this->getSize(), $this->getName());
    }
}

