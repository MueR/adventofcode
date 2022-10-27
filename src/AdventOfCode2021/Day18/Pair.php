<?php

namespace MueR\AdventOfCode\AdventOfCode2021\Day18;

use JetBrains\PhpStorm\Pure;

class Pair
{
    public ?Pair $leftPair = null;
    public ?Pair $rightPair = null;
    public ?Pair $parent = null;
    public int $endIndex = -1;
    public int $pairIndex = -1;

    public function __construct(
        public int|float|null $left = null,
        public int|float|null $right = null
    ) {
    }

    #[Pure]
    public function add(Pair $pair): Pair
    {
        $new = new Pair();
        $new->leftPair = $this;
        $new->rightPair = $pair;

        return $new;
    }

    public function split(): Pair
    {
        if ($this->left > 9) {
            $newLeft = new Pair(
                floor($this->left / 2),
                ceil($this->left / 2)
            );
            $newLeft->parent = $this;
            $this->leftPair = $newLeft;
            $this->left = null;
        }
        if ($this->right > 9) {
            $newRight = new Pair(
                floor($this->right / 2),
                ceil($this->right / 2)
            );
            $newRight->parent = $this;
            $this->rightPair = $newRight;
            $this->right = null;
        }

        return $this;
    }

    public function __toString(): string
    {
        return sprintf(
            '[%s,%s]',
            (string) ($this->left ?? (string) $this->leftPair),
            (string) ($this->right ?? (string) $this->rightPair),
        );
    }
}
