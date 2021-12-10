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
        $incompleteLines = array_filter($this->lines, static fn (array $line) => $line['score'] > 0);
        usort($incompleteLines, static fn (array $line1, array $line2) => $line2['score'] < $line1['score']);
        return $incompleteLines[count($incompleteLines) / 2]['score'];
    }

    protected function parseLine(int $index, bool $stopOnError = true): int
    {
        $stack = [];
        $tagsMatch = [
            ')' => '(',
            ']' => '[',
            '}' => '{',
            '>' => '<',
        ];
        $score = 0;
        $firstInvalid = null;
        $errorScore = [')' => 3, ']' => 57, '}' => 1197, '>' => 25137];
        $fixScore = ['(' => 1, '[' => 2, '{' => 3, '<' => 4];
        foreach (str_split($this->lines[$index]['input']) as $char) {
            switch ($char) {
                case '(':
                case '[':
                case '{':
                case '<':
                    $stack[] = $char;
                    break;
                case ')':
                case ']':
                case '}':
                case '>':
                    if (array_pop($stack) !== $tagsMatch[$char] && $firstInvalid === null) {
                        $this->lines[$index]['valid'] = false;
                        $firstInvalid = $errorScore[$char];
                    }
                    break;
            }
        }

        if ($firstInvalid === null) {
            $stack = array_reverse($stack);
            $this->lines[$index]['stack'] = implode('', str_replace(array_keys($errorScore), array_keys($fixScore), $stack));
            foreach ($stack as $char) {
                $score = ($score * 5) + $fixScore[$char];
            }
        }

        $this->lines[$index]['valid'] = $firstInvalid === null;
        $this->lines[$index]['score'] = $score;

        return (int)$firstInvalid;
    }

    protected function parse(): void
    {
        $this->lines = array_map(static fn (string $line) => ['input' => $line, 'valid' => null, 'score' => 0, 'stack' => null], explode("\n", $this->readText()));
    }
}
