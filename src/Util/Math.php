<?php

namespace MueR\AdventOfCode\Util;

class Math
{
    public static function sign(int $n): int
    {
        return ($n > 0) - ($n < 0);
    }
}
