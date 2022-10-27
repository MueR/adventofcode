<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day08;

use MueR\AdventOfCode\AbstractSolver;
use MueR\AdventOfCode\Util\StringUtil;

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

    protected function mapLine(array $input): void
    {
        $this->signalsPerDigit = array_fill_keys(range(0, 9), '');
        /** @noinspection PackedHashtableOptimizationInspection */
        $tests = [
            // 1 is the only one with 2 segments
            '1' => static fn (string $pattern) => strlen($pattern) === 2,
            // 7 is the only one with 3 segments
            '7' => static fn (string $pattern) => strlen($pattern) === 3,
            // 4 is the only one with 4 segments
            '4' => static fn (string $pattern) => strlen($pattern) === 4,
            // 8 is the only one with 7 segments
            '8' => static fn (string $pattern) => strlen($pattern) === 7,
            // 9 is 6 segments, matches segments for 4
            '9' => fn (string $pattern) => strlen($pattern) === 6
                && StringUtil::matchesAll($pattern, $this->signalsPerDigit[4]),
            // 0 is 6 segments, matching 1's segments (9 is already out)
            '0' => fn (string $pattern) => strlen($pattern) === 6
                && StringUtil::matchesAll($pattern, $this->signalsPerDigit[1]),
            // 6 is 6 segments, the only one left
            '6' => static fn (string $pattern) => strlen($pattern) === 6,
            // 3 is 5 segments and matches 1's segments
            '3' => fn (string $pattern) => strlen($pattern) === 5
                && StringUtil::matchesAll($pattern, $this->signalsPerDigit[1]),
            // 5 is 5 segments, and 9 has all the segments of 5
            '5' =>  fn (string $pattern) => strlen($pattern) === 5
                && StringUtil::matchesAll($this->signalsPerDigit[9], $pattern),
            // 2 is the only one remaining
            '2' => static fn (string $pattern) => true,
        ];
        foreach ($tests as $digit => $test) {
            foreach ($input as $key => $pattern) {
                if (!$test($pattern)) {
                    continue;
                }
                unset($input[$key]);
                $this->signalsPerDigit[$digit] = $pattern;
                break;
            }
        }
    }

    protected function parse(): void
    {
        $sequences = explode("\n", $this->readText());
        foreach ($sequences as $sequence) {
            $data = explode(' | ', $sequence);
            $this->sequences[] = [
                'patterns' => array_map([StringUtil::class, 'sort'], explode(' ', $data[0])),
                'shown' => array_map([StringUtil::class, 'sort'], explode(' ', $data[1])),
            ];
        }
    }
}

