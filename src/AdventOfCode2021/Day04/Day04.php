<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day04;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 4 puzzle.
 *
 * @property array{int} $input
 */
class Day04 extends AbstractSolver
{
    /**
     * @var array{int}
     */
    private array $numbers = [];

    private array $cards = [];

    public function partOne(): int
    {
        foreach ($this->numbers as $number) {
            foreach ($this->cards as $card => $content) {
                if ($this->markNumber($card, $number)) {
                    $remaining = $this->getRemainingNumberScore($card);

                    return $number * $remaining;
                }
            }
        }

        return -1;
    }

    public function partTwo(): int
    {
        return -1;
    }

    protected function showCard(int $card): void
    {
        $t = 0;
        foreach ($this->cards[$card] as $line) {
            $r = 0;
            foreach ($line as $n) {
                printf('%4d ', $n);
                if ($n >= 0) {
                    $r += $n;
                }
            }
            printf(' :: Remaining %d', $r);
            $t += $r;
            print "\n";
        }
        print 'Card total: ' . $t;
        print "\n";
    }

    protected function markNumber(int $card, int $numberDrawn): bool
    {
        foreach ($this->cards as $lines) {
            foreach ($lines as $row => $line) {
                foreach ($line as $col => $number) {
                    if ($number === $numberDrawn) {
                        $this->cards[$card][$row][$col] = $numberDrawn === 0 ? -100 : -$number;

                        $bingo = $this->checkBingo($this->cards[$card], $row, $col);
                        if ($bingo) {
                            printf("BINGO Card %d Num %d\n\n", $card + 1, $number);
                            $this->showCard($card);
                        }
                        return $bingo;
                    }
                }
            }
        }

        return false;
    }

    protected function getRemainingNumberScore($card): int
    {
        $remaining = 0;
        foreach ($this->cards[$card] as $line) {
            foreach ($line as $number) {
                if ($number >= 0) {
                    $remaining += $number;
                }
            }
        }

        return $remaining;
    }

    protected function checkBingo(array $card, int $row, int $col): bool
    {
        return $this->isRowFilled($card, $row) || $this->isColFilled($card, $col);
    }

    protected function isRowFilled(array $card, int $row): bool
    {
        return empty(array_filter($card[$row], static fn (int $number) => $number >= 0));
    }

    protected function isColFilled(array $card, int $col): bool
    {
        return empty(array_filter($card, static fn (array $line) => $line[$col] >= 0));
    }

    protected function readTextInput(): array
    {
        $testInput = <<<TEST
7,4,9,5,11,17,23,2,0,14,21,24,10,16,13,6,15,25,12,22,18,20,8,19,3,26,1

22 13 17 11  0
 8  2 23  4 24
21  9 14 16  7
 6 10  3 18  5
 1 12 20 15 19

 3 15  0  2 22
 9 18 13 17  5
19  8  7 25 23
20 11 10 24  4
14 21 16 12  6

14 21 17 24  4
10 16 15  9 19
18  8 23 26 20
22 11 13  6  5
 2  0 12  3  7
TEST;

        $this->cards = [];
        $this->numbers = [];
        $content = $this->test ? $testInput : $this->readText();
        $cards = explode("\n\n", $content);
        $this->numbers = array_map(static fn ($num) => (int)$num, explode(',', array_shift($cards)));

        foreach ($cards as $index => $cardText) {
            foreach (explode("\n", $cardText) as $row => $line) {
                $col = 0;
                foreach (explode(' ', $line) as $number) {
                    if (trim($number) === '') {
                        continue;
                    }
                    $this->cards[$index][$row][$col++] = (int)$number;
                }
            }
        }

        return $this->numbers;
    }
}
