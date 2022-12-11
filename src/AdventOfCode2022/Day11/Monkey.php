<?php

namespace MueR\AdventOfCode\AdventOfCode2022\Day11;

use Ds\Queue;

class Monkey implements \Stringable
{
    /** @var Queue<int> */
    public Queue $items;
    public array $operation;

    public function __construct(
        public readonly int $number,
        string $operation,
        public readonly int $divisibleBy,
        public readonly int $monkeyIfTrue,
        public readonly int $monkeyIfFalse,
    ) {
        $this->operation = explode(' ', $operation);
        $this->items = new Queue();
    }

    public function operation(int $item): int
    {
        $right = $this->operation[2] === 'old' ? $item : (int) $this->operation[2];

        return match ($this->operation[1]) {
            '+' => $item + $right,
            '*' => $item * $right,
        };
    }

    public function catch(int $item): void
    {
        $this->items->push($item);
    }

    public function __toString(): string
    {
        return sprintf('Monkey<%s>', $this->number);
    }

    public static function fromString(string $text): self
    {
        $input = explode(PHP_EOL, $text);
        $monkey = new self(
            (int) substr($input[0], 7),
            substr($input[2], strpos($input[2], '=') + 2),
            (int) substr($input[3], strrpos($input[3], ' ') + 1),
            (int) substr($input[4], strrpos($input[4], ' ') + 1),
            (int) substr($input[5], strrpos($input[5], ' ') + 1),
        );

        $items = explode(', ', substr($input[1], strpos($input[1], ': ') + 1));
        foreach ($items as $item) {
            $monkey->catch((int) $item);
        }

        return $monkey;
    }
}
