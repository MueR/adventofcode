<?php

namespace MueR\AdventOfCode\Util;

class Util
{
    public static function sign(int $n): int
    {
        return ($n > 0) - ($n < 0);
    }
}
