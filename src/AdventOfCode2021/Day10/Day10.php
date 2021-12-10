<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day10;

use MueR\AdventOfCode\AbstractSolver;

class Day10 extends AbstractSolver
{
    protected array $lines = [];

    public function partOne(): int|float
    {
        $scores = [];
        foreach ($this->lines as $i => $line) {
            $scores[] = $this->parseLine($i);
        }

        return array_sum($scores);
    }

    public function partTwo(): int|float
    {
        $incompleteLines = array_filter($this->lines, static fn(array $line) => $line['score'] > 0);
        usort($incompleteLines, static fn(array $line1, array $line2) => $line2['score'] < $line1['score']);

        return $incompleteLines[count($incompleteLines) / 2]['score'];
    }

    protected function parseLine(int $index, bool $stopOnError = true): int
    {
        $stack = [];
        $firstInvalid = null;
        $tagsMatch = [')' => '(', ']' => '[', '}' => '{', '>' => '<'];
        $errorScore = [')' => 3, ']' => 57, '}' => 1197, '>' => 25137];
        $fixScore = ['(' => 1, '[' => 2, '{' => 3, '<' => 4];
        foreach (str_split($this->lines[$index]['input']) as $char) {
            if (in_array($char, $tagsMatch, true)) {
                $stack[] = $char;
                continue;
            }
            if ($firstInvalid === null && array_key_exists($char, $tagsMatch) && array_pop($stack) !== $tagsMatch[$char]) {
                $firstInvalid = $errorScore[$char];
            }
        }

        if ($firstInvalid === null) {
            $stack = array_reverse($stack);
            foreach ($stack as $char) {
                $this->lines[$index]['score'] = ($this->lines[$index]['score'] * 5) + $fixScore[$char];
            }
        }

        return (int)$firstInvalid;
    }

    protected function parse(): void
    {
        $this->lines = array_map(static fn (string $line) => ['input' => $line, 'score' => 0], explode("\n", $this->readText()));
    }
}
