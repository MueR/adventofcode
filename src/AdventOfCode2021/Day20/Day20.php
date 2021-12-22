<?php

declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2021\Day20;

use MueR\AdventOfCode\AbstractSolver;
use SplFixedArray;

/**
 * Day 20 puzzle.
 *
 * @property array{int} $input
 */
class Day20 extends AbstractSolver
{
    private int $width;
    private int $height;
    private array $image;
    private array $algorithm;

    public function partOne() : int
    {
        $image = $this->runEnhancement($this->image, 2);
        return $this->count($image);
    }

    public function partTwo() : int
    {
        $image = $this->runEnhancement($this->image, 50);
        return $this->count($image);
    }

    protected function runEnhancement(array $image, int $times, bool $displayImage = false): array
    {
        $infinitePixel = false;
        for ($i = 0; $i < $times; $i++) {
            $image = $this->enhance($image, $infinitePixel);
            $infinitePixel = $infinitePixel ? $this->algorithm[511] : $this->algorithm[0];
        }

        return $image;
    }

    protected function count(array $image): int
    {
        return array_sum(array_map(static fn (array $line) => array_sum($line), $image));
    }

    protected function enhance(array $image, bool $inf): array
    {
        $xMax = count($image[0]) + 2;
        $enhanced = array_fill(0, count($image) + 2, (new SplFixedArray($xMax))->toArray());
        for ($y = -1, $yMax = count($enhanced) + 2; $y < $yMax; $y++) {
            for ($x = -1; $x < $xMax; $x++) {
                $bin = [
                    $this->getPixel($image, $x - 1, $y - 1, $inf),
                    $this->getPixel($image, $x + 0, $y - 1, $inf),
                    $this->getPixel($image, $x + 1, $y - 1, $inf),
                    $this->getPixel($image, $x - 1, $y + 0, $inf),
                    $this->getPixel($image, $x + 0, $y + 0, $inf),
                    $this->getPixel($image, $x + 1, $y + 0, $inf),
                    $this->getPixel($image, $x - 1, $y + 1, $inf),
                    $this->getPixel($image, $x + 0, $y + 1, $inf),
                    $this->getPixel($image, $x + 1, $y + 1, $inf),
                ];

                $decimal = 0;
                foreach ($bin as $bool) {
                    $decimal <<= 1;
                    if ($bool) {
                        $decimal |= 1;
                    }
                }


                $enhanced[$y + 1][$x + 1] = $this->algorithm[$decimal];
            }
        }

        return $enhanced;
    }

    protected function getPixel(array $image, int $x, int $y, bool $inf): bool
    {
        return $image[$y][$x] ?? $inf;
    }

    protected function display(array $image): void
    {
        foreach ($image as $row) {
            print implode('', array_map(static fn (bool $pixel) => $pixel ? '█' : '.', $row)) . "\n";
        }
    }

    protected function parse(): void
    {
        $input = explode("\n", trim($this->readText()));
        $alg = array_shift($input);
        $this->algorithm = array_map(static fn (string $char) => $char === '#', str_split($alg));
        array_shift($input);

        $this->width = strlen($input[0]);
        $this->height = count($input);

        $this->image = array_fill_keys(range(0, $this->height), []);
        for ($y = 0; $y < $this->height; $y++) {
            for ($x = 0; $x < $this->width; $x++) {
                $this->image[$y][$x] = ($input[$y][$x] === '#');
            }
        }
    }

}

