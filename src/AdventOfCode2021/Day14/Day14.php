<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day14;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 14 puzzle.
 *
 * @property array{int} $input
 */
class Day14 extends AbstractSolver
{
    /**
     * @var array<string, int>
     */
    protected array $pairs = [];
    /**
     * @var array<string, string>
     */
    protected array $rules = [];
    protected string $polymer;

    public function partOne(): int
    {
        return $this->runSteps($this->pairs, 10);
    }

    public function partTwo(): int
    {
        return $this->runSteps($this->pairs, 40);
    }

    protected function runSteps(array $input, int $steps): int
    {
        $result = $input;
        for ($i = 0; $i < $steps; $i++) {
            $result = $this->solveStep($result);
        }
        $counts = $this->getCharacters($result);
        sort($counts, SORT_ASC);

        return $counts[array_key_last($counts)] - $counts[array_key_first($counts)];
    }

    protected function getCharacters(array $pairs): array
    {
        $counts = $this->getCounts($pairs);
        $lastChar = substr($this->polymer, -1);
        $counts[$lastChar] = 1 + (!array_key_exists($lastChar, $counts) ? 0 : $counts[$lastChar]);

        return $counts;
    }

    protected function getCounts(array $pairs): array
    {
        $result = [];
        foreach ($pairs as $pair => $count) {
            $char = $pair[0];
            $result[$char] = $count + (!array_key_exists($char, $result) ? 0 : $result[$char]);
        }

        return $result;
    }

    protected function solveStep(array $input): array
    {
        $result = $input;
        foreach ($input as $pair => $count) {
            if ($count === 0) {
                continue;
            }

            $insert = $this->rules[$pair] ?? null;
            if ($insert === null) {
                continue;
            }

            $newPair1 = $pair[0] . $insert;
            $newPair2 = $insert . $pair[1];

            $result[$pair] -= $count;
            $result[$newPair1] = $count + (!array_key_exists($newPair1, $result) ? 0 : $result[$newPair1]);
            $result[$newPair2] = $count + (!array_key_exists($newPair2, $result) ? 0 : $result[$newPair2]);
        }

        return $result;
    }

    protected function parse(): void
    {
        [$this->polymer, $rules] = explode("\n\n", $this->readText());
        foreach (explode("\n", $rules) as $line) {
            [$pair, $insert] = explode(' -> ', $line);
            $this->rules[$pair] = $insert;
        }

        for ($i = 0, $l = strlen($this->polymer) - 1; $i < $l; $i++) {
            $pair = $this->polymer[$i] . $this->polymer[$i + 1];
            if (!array_key_exists($pair, $this->pairs)) {
                $this->pairs[$pair] = 0;
            }
            $this->pairs[$pair]++;
        }
    }
}
