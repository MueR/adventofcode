<?php

namespace MueR\AdventOfCode\AdventOfCode2021\Day12;

class Cave
{
    public bool $start = false;
    public bool $end = false;
    public bool $big = false;
    public bool $visited = false;
    /** @var Cave[] */
    public array $linksTo = [];

    public function __construct(public string $name)
    {
        $this->start = $this->name === 'start';
        $this->end = $this->name === 'end';
        $this->big = $this->end || $this->start || $this->name !== strtolower($this->name);
    }

    public function link(Cave $cave): void
    {
        if ($cave->start) {
            // do not link starting cave, we never go back to it.
            return;
        }
        $this->linksTo[] = $cave;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
