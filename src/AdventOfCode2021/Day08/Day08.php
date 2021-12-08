<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day08;

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
            // 1 is the only one with 2 segments
            '1' => fn (string $pattern) => strlen($pattern) === 2,
            // 7 is the only one with 3 segments
            '7' => fn (string $pattern) => strlen($pattern) === 3,
            // 4 is the only one with 4 segments
            '4' => fn (string $pattern) => strlen($pattern) === 4,
            // 8 is the only one with 7 segments
            '8' => fn (string $pattern) => strlen($pattern) === 7,
            // 9 matches segments for 4 and 7 (5 segments) plus 1 extra
            '9' => fn (string $pattern) => strlen($pattern) === 6 && similar_text($pattern, $this->sortPattern($this->signalsPerDigit[4] . $this->signalsPerDigit[7])) === 5,
            // 0 is 6 segments, matching 7's segments (9 is already out)
            '0' => fn (string $pattern) => strlen($pattern) === 6 && similar_text($pattern, $this->signalsPerDigit[7]) === 3,
            // 3 is 5 segments and matches 1's segments
            '3' => fn (string $pattern) => strlen($pattern) === 5 && similar_text($pattern, $this->signalsPerDigit[1]) === 2,
            // 5 is 5 segments, matching 3 of 4 segments with 4.
            '5' => fn (string $pattern) => strlen($pattern) === 5 && similar_text($pattern, $this->signalsPerDigit[4]) === 3,
            // 2 is the only one remaining with length 5
            '2' => fn (string $pattern) => strlen($pattern) === 5,
            // aaaaand that just leaves 6.
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

