<?php

namespace MueR\AdventOfCode\AdventOfCode2022\Day07;

class Directory
{
    private readonly string $fullPath;

    private int $cumulativeSize = 0;

    /** @var Directory[] */
    private array $children = [];

    private int $size = 0;

    public function __construct(
        private readonly string $name,
        private readonly ?self $parent = null,
    ) {
        $this->fullPath = ($this->parent ? $this->parent->getFullPath() : '') . $this->name . '/';
    }

    public function getFullPath(): string
    {
        return $this->fullPath;
    }

    public function getParent(): ?Directory
    {
        return $this->parent;
    }

    public function getChild(string $name): Directory
    {
        return $this->children[$name];
    }

    public function getCumulativeSize(): int
    {
        return $this->cumulativeSize;
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
}
