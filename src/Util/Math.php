<?php

namespace MueR\AdventOfCode\Util;

class Math
{
    /**
     * Returns either a positive or negative +/- 1, indicating the sign of a number passed into the argument.
     */
    public static function sign(int $n): int
    {
        return ($n > 0) - ($n < 0);
    }
}
