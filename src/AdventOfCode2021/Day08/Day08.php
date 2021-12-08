<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day08;

use JetBrains\PhpStorm\ArrayShape;
use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 8 puzzle.
 *
 * @property array{int} $input
 */
class Day08 extends AbstractSolver
{
    private array $sequences = [];
    private array $signalsPerDigit = [];

    public function partOne() : int
    {
        $occurrences = 0;
        foreach ($this->sequences as $sequence) {
            foreach ($sequence['shown'] as $pattern) {
                $occurrences += match (strlen($pattern)) {
                    2, 3, 4, 7 => 1,
                    default => 0,
                };
            }
        }

        return $occurrences;
    }

    public function partTwo() : int
    {
        $results = [];
        foreach ($this->sequences as $sequence) {
            $this->mapLine($sequence['patterns']);
            $output = '';
            foreach ($sequence['shown'] as $pattern) {
                $output .= array_search($pattern, $this->signalsPerDigit, true);
            }
            $results[] = (int)$output;
        }

        return array_sum($results);
    }

    protected function mapLine(array $input)
    {
        $this->signalsPerDigit = array_fill_keys(range(0, 9), '');
        $tests = [
            '1' => fn (string $pattern) => strlen($pattern) === 2,
            '7' => fn (string $pattern) => strlen($pattern) === 3,
            '4' => fn (string $pattern) => strlen($pattern) === 4,
            '8' => fn (string $pattern) => strlen($pattern) === 7,
            '9' => fn (string $pattern) => strlen($pattern) === 6 && similar_text($pattern, $this->sortPattern($this->signalsPerDigit[4] . $this->signalsPerDigit[7])) === 5,
            '0' => fn (string $pattern) => strlen($pattern) === 6 && similar_text($pattern, $this->signalsPerDigit[7]) === 3,
            '3' => fn (string $pattern) => strlen($pattern) === 5 && similar_text($pattern, $this->signalsPerDigit[1]) === 2,
            '5' => fn (string $pattern) => strlen($pattern) === 5 && similar_text($pattern, $this->signalsPerDigit[4]) === 3,
            '2' => fn (string $pattern) => strlen($pattern) === 5,
            '6' => fn (string $pattern) => true,
        ];
        foreach ($tests as $digit => $test) {
            foreach ($input as $key => $pattern) {
               if ($test($pattern)) {
                   unset($input[$key]);
                   $this->signalsPerDigit[$digit] = $pattern;
                   break;
               }
            }
        }
    }

    protected function sortPattern(string $pattern): string
    {
        $letters = array_unique(str_split($pattern));
        sort($letters);
        return implode('', $letters);
    }

    protected function parse(): void
    {
        $sequences = explode("\n", $this->readText());
        foreach ($sequences as $sequence) {
            $data = explode(' | ', $sequence);
            $this->sequences[] = [
                'patterns' => array_map([$this, 'sortPattern'], explode(' ', $data[0])),
                'shown' => array_map([$this, 'sortPattern'], explode(' ', $data[1])),
            ];
        }
    }
}

