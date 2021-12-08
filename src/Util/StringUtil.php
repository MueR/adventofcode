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
     * Alphabetically sort characters in a string.
     */
    public static function sort(string $string): string
    {
        $letters = array_unique(str_split($string));
        sort($letters, SORT_STRING);
        return implode('', $letters);
    }
}
