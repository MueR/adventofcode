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
        $winners = [];
        $totalCards = count($this->cards);
        foreach ($this->numbers as $number) {
            foreach ($this->cards as $card => $content) {
                if ($this->markNumber($card, $number) && !in_array($card, $winners, true)) {
                    $winners[] = $card;
                }
                if (count($winners) === $totalCards) {
                    return $number * $this->getRemainingNumberScore($card);
                }
            }
        }

        return -1;
    }

    protected function markNumber(int $card, int $numberDrawn): bool
    {
        foreach ($this->cards[$card] as $row => $line) {
            foreach ($line as $col => $number) {
                if ($this->cards[$card][$row][$col] === $numberDrawn) {
                    $this->cards[$card][$row][$col] = $numberDrawn === 0 ? -100 : -$number;

                    return $this->checkBingo($this->cards[$card], $row, $col);
                }
            }
        }

        return false;
    }

    protected function getRemainingNumberScore($card): int
    {
        return array_sum(array_map(static fn (array $line) => array_sum(array_filter($line, static fn (int $num) => $num >= 0)), $this->cards[$card]));
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

    protected function parse(): void
    {
        $this->cards = [];
        $this->numbers = [];
        $content = $this->readText();
        $cards = explode("\n\n", $content);
        $this->numbers = array_map(static fn ($num) => (int)$num, explode(',', array_shift($cards)));

        foreach ($cards as $index => $cardText) {
            foreach (explode("\n", $cardText) as $row => $line) {
                $col = 0;
                foreach (array_filter(explode(' ', $line), static fn (string $entry) => $entry !== '') as $number) {
                    $this->cards[$index][$row][$col++] = (int)$number;
                }
            }
        }
    }
}
