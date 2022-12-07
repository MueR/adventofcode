<?php

namespace MueR\AdventOfCode\AdventOfCode2022\Day07;

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
        private ?self  $parent = null,
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