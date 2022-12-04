<?php

namespace MueR\AdventOfCode\Exception;

class InputFileNotFoundException extends \RuntimeException
{
    public function __construct(
        string $message = '',
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct(sprintf('Input file "%s" does not exist.', $message), $code, $previous);
    }
}
