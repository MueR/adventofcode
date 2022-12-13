<?php

namespace MueR\AdventOfCode\Util;

class StringUtil
{
    /**
     * Check if the entirety of string two matches string one.
     */
    public static function matchesAll(string $one, string $two): bool
    {
        for ($i = 0, $l = strlen($two); $i < $l; $i++) {
            if (!str_contains($one, $two[$i])) {
                return false;
            }
        }

        return true;
    }

    /**
     * Split a string into an int array.
     *
     * @return int[]
     */
    public static function toIntArray(string $string): array
    {
        return array_map(static fn (string $n) => (int) $n, str_split($string));
    }

    /**
     * Alphabetically sort characters in a string.
     */
    public static function sort(string $string): string
    {
        $letters = array_unique(str_split($string));
        sort($letters, SORT_STRING);
        return implode('', $letters);
    }
}
