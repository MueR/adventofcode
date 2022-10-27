<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day18;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 18 puzzle.
 *
 * @property array{int} $input
 */
class Day18 extends AbstractSolver
{
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

            $this->debug($pair, true);
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
            $this->debug($pair, true);
            if ($wentBoom && $depth === 3) {
                $pair->rightPair = null;
                $pair->right = 0;
            }

        }

        $this->debug($pair, true);

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
        } elseif (!$added) {
            $flattened[] = $pair;
            $pair->pairIndex = count($flattened) - 1;
        }
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

    private function debug(Pair $pair, bool $lineFeed = false): void
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
        if ($lineFeed) {
            echo "\n";
        }
    }
}
