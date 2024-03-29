<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day16;

use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 16 puzzle.
 *
 * @property array{int} $input
 */
class Day16 extends AbstractSolver
{
    /** @var Packet[] */
    private array $packets = [];

    public function __construct(bool $test = false)
    {
        parent::__construct($test);
        error_reporting(0);
    }

    public function partOne(): int
    {
        return $this->packets[0]->versionSum();
    }

    public function partTwo(): int
    {
        return $this->packets[0]->value();
    }

    protected function readPackets(array $bits, int $index, ?Packet $parent = null): int
    {
        $currentIndex = $index;
        if ($currentIndex >= count($bits) - 1) {
            return $index;
        }

        $versionString = $bits[$currentIndex++] . $bits[$currentIndex++] . $bits[$currentIndex++];
        $typeString = $bits[$currentIndex++] . $bits[$currentIndex++] . $bits[$currentIndex++];
        $version = $this->toInt($versionString);
        $type = $this->toInt($typeString);

        $packet = new Packet($version, $type);
        if ($parent) {
            $parent->subPackets[] = $packet;
        } else {
            $this->packets[] = $packet;
        }

        if ($type === PacketType::LITERAL) {
            $currentIndex = $this->readLiteral($bits, $currentIndex, $packet);
        } else {
            $lengthId = $bits[$currentIndex++];
            if ($lengthId === '0') {
                $currentIndex = $this->readFixedLength($bits, $currentIndex, $packet);
            } else {
                $currentIndex = $this->readFixedCount($bits, $currentIndex, $packet);
            }
        }

        return $currentIndex - $index;
    }

    protected function readLiteral(array $bits, int $index, Packet $packet): int
    {
        $readFurther = true;
        $value = '';
        while ($readFurther) {
            $readFurther = $bits[$index++] === '1';
            $value .= implode('', array_slice($bits, $index, 4));
            $index += 4;
        }

        $packet->value = $this->toInt($value);

        return $index;
    }

    protected function readFixedCount(array $bits, int $index, Packet $parent): int
    {
        $parseString = implode('', array_slice($bits, $index, 11));
        $index += 11;
        $length = $this->toInt($parseString);

        while (count($parent->subPackets) < $length) {
            $index += $this->readPackets($bits, $index, $parent);
        }

        return $index;
    }

    protected function readFixedLength(array $bits, int $index, Packet $parent): int
    {
        $parseString = implode('', array_slice($bits, $index, 15));
        $index += 15;
        $length = $this->toInt($parseString);
        $end = $index + $length;
        while ($index < $end) {
            $index += $this->readPackets($bits, $index, $parent);
        }

        return $index;
    }

    private function toInt(string $val): int
    {
        return intval($val, 2);
    }

    private function hex2bin(string $char): string
    {
        return match ($char) {
            '0' => '0000',
            '1' => '0001',
            '2' => '0010',
            '3' => '0011',
            '4' => '0100',
            '5' => '0101',
            '6' => '0110',
            '7' => '0111',
            '8' => '1000',
            '9' => '1001',
            'A' => '1010',
            'B' => '1011',
            'C' => '1100',
            'D' => '1101',
            'E' => '1110',
            'F' => '1111',
        };
    }

    protected function parse(): void
    {
        $binString = '';
        foreach (str_split($this->readText()) as $char) {
            $binString .= $this->hex2bin($char);
        }

        $this->readPackets(str_split($binString), 0);
    }
}

