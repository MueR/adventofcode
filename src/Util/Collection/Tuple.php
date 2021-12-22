<?php

namespace MueR\AdventOfCode\Util\Collection;

use JetBrains\PhpStorm\Pure;

class Tuple
{
    public function __construct(private float|int $left = 0, private float|int $right = 0)
    {
    }

    public function getLeft(): float|int
    {
        return $this->left;
    }

    public function getRight(): float|int
    {
        return $this->right;
    }

    #[Pure]
    public function incrementLeft(float|int $amount): Tuple
    {
        return new static($this->left + $amount, $this->right);
    }

    #[Pure]
    public function incrementRight(float|int $amount): Tuple
    {
        return new static($this->left, $this->right + $amount);
    }

    #[Pure]
    public function flip(): Tuple
    {
        return new static($this->right, $this->left);
    }

    #[Pure]
    public function add(Tuple $tuple): Tuple
    {
        return new static($this->left + $tuple->left, $this->right + $tuple->right);
    }

    #[Pure]
    public function times(int $times): Tuple
    {
        return new static($this->left * $times, $this->right * $times);
    }

    public function __toString(): string
    {
        return '[' . $this->left . ',' . $this->right . ']';
    }
}
