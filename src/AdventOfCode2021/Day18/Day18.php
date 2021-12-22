<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day18;

use ArrayIterator;
use JetBrains\PhpStorm\Pure;
use MueR\AdventOfCode\AbstractSolver;
use SplFileObject;

/**
 * Day 18 puzzle.
 *
 * @property array{int} $input
 */
class Day18 extends AbstractSolver
{
    private Pair $root;

    public function partOne(): float
    {
        return $this->magnitude($this->addLines());
    }

    public function partTwo(): int
    {
        return -1;
    }

    protected function addLines(): Pair
    {
        $pair = $this->parseLine(array_shift($this->input));
        print $pair . "\n\n";
        $reduce = true;
        $i = 0;
        while ($reduce) {
            $reduce = $this->reduce($pair);
            print $i++ . ':' . $pair . "\n\n";
        }
        foreach ($this->input as $line) {
            $next = $this->parseLine($line);
            $pair = $pair->add($next);
            $reduce = true;
            while ($reduce) {
                $reduce = $this->reduce($pair);
            }
        }
        print ' = ' . $this->magnitude($pair) . "\n";

        return $pair;
    }

    protected function reduce(Pair $pair): bool
    {
        $flattened = [];
        $this->flatten($pair, $flattened);
        foreach ($flattened as $flat) {
            echo $flat . ',';
        }
        print "\n\n";
        $exploded = $this->explode($pair, 0, $flattened);
        if ($exploded) {
            return true;
        }

        return $pair->split() !== $pair;
    }

    /** @param Pair[] $pairs */
    public function explode(Pair $pair, int $depth, array &$pairs): bool
    {
        $wentBoom = false;
        if ($depth === 4) {
            for ($i = $pair->pairIndex - 1; $i >= 0; $i--) {
                $current = $pairs[$i];
                if ($current->rightPair !== null) {
                    $current->right += $pair->left;
                    $this->debug($current);
                    break;
                }
                if ($current->leftPair !== null) {
                    $current->left += $pair->left;
                    $this->debug($current);
                    break;
                }
            }
            for ($i = $pair->pairIndex + 1, $max = count($pairs); $i < $max; $i++) {
                $current = $pairs[$i];
                if ($current->leftPair !== null) {
                    print "\nUpdating pair left: {$current->left} + {$pair->right} = ";
                    $current->left += $pair->right;
                    print "{$current->left}\n";
                    break;
                }
                if ($current->rightPair !== null) {
                    print "\nUpdating pair right: {$current->left} + {$pair->right} = ";
                    $current->right += $pair->right;
                    print "{$current->right}\n";
                    break;
                }
            }

            print $this->debug($pair) . "\n";
            return true;
        }

        if ($pair->leftPair !== null) {
            $wentBoom = $this->explode($pair->leftPair, $depth + 1, $pairs);
            if ($wentBoom && $depth === 3) {
                $pair->leftPair = null;
                $pair->left = 0;
            }
        }
        if (!$wentBoom && $pair->rightPair !== null) {
            $wentBoom = $this->explode($pair->rightPair, $depth + 1, $pairs);
            print $this->debug($pair) . "\n";
            if ($wentBoom && $depth === 3) {
                $pair->rightPair = null;
                $pair->right = 0;
            }

        }

        print $this->debug($pair) . "\n";

        return $wentBoom;
    }

    public function flatten(Pair $pair, array &$flattened): void
    {
        $added = false;
        if ($pair->leftPair !== null) {
            $this->flatten($pair->leftPair, $flattened);
        } else {
            $flattened[] = $pair;
            $added = true;
            $pair->pairIndex = count($flattened) - 1;
        }

        if ($pair->rightPair !== null) {
            $this->flatten($pair->rightPair, $flattened);
        } else if (!$added) {
            $flattened[] = $pair;
            $pair->pairIndex = count($flattened) - 1;
        }
    }

    protected function split(Pair $pair): bool
    {
        $split = false;
        if ($pair->leftPair === null && $pair->left > 9) {
            $new = new Pair(floor($pair->left / 2), ceil($pair->right / 2));
            $pair->leftPair = $new;
            $new->parent = $pair;

            return true;
        } else if ($pair->leftPair !== null) {
            $split = $this->split($pair->leftPair);
        }

        if (!$split) {
            if ($pair->rightPair === null && $pair->right > 9) {
                $new = new Pair(floor($pair->right / 2), ceil($pair->right / 2.0));
                $pair->rightPair = $new;
                $new->parent = $pair;

                return true;
            }
            if ($pair->rightPair !== null) {
                $split = $this->split($pair->rightPair);
            }
        }

        return $split;
    }

    public function magnitude(Pair $pair): float
    {
        if ($pair->leftPair !== null) {
            $pair->left = $this->magnitude($pair->leftPair);
        }
        if ($pair->rightPair !== null) {
            $pair->right = $this->magnitude($pair->rightPair);
        }

        return (3 * $pair->left) + (2 * $pair->right);
    }

    protected function parseLine(string $line, int $index = 0): Pair
    {
        $pair = new Pair();

        if ($line[$index + 1] === '[') {
            $pair->leftPair = $this->parseLine($line, $index + 1);
            $pair->leftPair->parent = $pair;
            $index = $pair->leftPair->endIndex + 1;
        } else {
            $pair->left = (int) substr($line, $index + 1, 2);
            $index += 3;
        }

        if ($line[$index] === '[') {
            $pair->rightPair = $this->parseLine($line, $index);
            $pair->rightPair->parent = $pair;
            $pair->endIndex = $pair->rightPair->endIndex + 1;
        } else {
            $pair->right = (int) substr($line, $index, 2);
            $pair->endIndex = $index + 2;
        }

        return $pair;
    }

    private function debug(Pair $pair): void
    {
        echo '[';
        if ($pair->leftPair === null) {
            echo $pair->left;
        } else {
            $this->debug($pair->leftPair);
        }
        echo ',';
        if ($pair->rightPair === null) {
            echo $pair->right;
        } else {
            $this->debug($pair->rightPair);
        }
        echo ']';
    }
}

class Pair
{
    public ?Pair $leftPair = null;
    public ?Pair $rightPair = null;
    public ?Pair $parent = null;
    public int $endIndex = -1;
    public int $pairIndex = -1;

    public function __construct(public int|float|null $left = null, public int|float|null $right = null)
    {
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
        return '[' . (string) ($this->left ?? (string) $this->leftPair) . ',' . (string) ($this->right ?? (string) $this->rightPair) . ']';
    }
}
