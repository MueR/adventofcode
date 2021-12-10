<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day10;

use MueR\AdventOfCode\AbstractSolver;
use MueR\AdventOfCode\Util\Collection\Sequence;

class Day10 extends AbstractSolver
{
    protected Sequence $lines;

    public function partOne(): int
    {
        $scores = $this->lines->map(fn (array $line, int $index) => $this->parseLine($line, $index));

        return array_sum($scores->all());
    }

    public function partTwo(): int
    {
        $scores = $this->lines->filter(static fn (array $line) => $line['score'] > 0);
        $scores->sortWith(static fn (array $line1, array $line2) => $line2['score'] < $line1['score']);

        return $scores->get((int) floor($scores->count() / 2))['score'];
    }

    protected function parseLine(array $line, int $index): int
    {
        $stack = [];
        $firstInvalid = null;
        $tagsMatch = [')' => '(', ']' => '[', '}' => '{', '>' => '<'];
        $errorScore = [')' => 3, ']' => 57, '}' => 1197, '>' => 25137];
        $fixScore = ['(' => 1, '[' => 2, '{' => 3, '<' => 4];
        foreach (str_split($line['input']) as $char) {
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
                $line['score'] = ($line['score'] * 5) + $fixScore[$char];
            }
            $this->lines->update($index, $line);
        }

        return (int) $firstInvalid;
    }

    protected function parse(): void
    {
        $this->lines = new Sequence(array_map(static fn (string $line) => ['input' => $line, 'score' => 0], explode("\n", $this->readText())));
    }
}
