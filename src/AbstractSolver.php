<?php

declare(strict_types=1);

namespace MueR\AdventOfCode2021;

use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

abstract class AbstractSolver
{
    private Stopwatch $stopwatch;
    protected string|array $input;
    private string $ns;
    public int $day;

    public function __construct()
    {
        $this->ns = substr(substr(get_class($this), 0, strrpos(get_class($this), '\\')), -5);
        $this->day = (int)substr($this->ns, -2);
        $this->stopwatch = new Stopwatch(true);
        $this->stopwatch->start($this->ns);
    }

    abstract public function partOne(): int;
    abstract public function partTwo(): int;

    public function lap(): StopwatchEvent
    {
        return $this->stopwatch->lap($this->ns);
    }

    public function stop(): StopwatchEvent
    {
        return $this->stopwatch->stop($this->ns);
    }

    protected function readInput(): void
    {
        $this->input = require __DIR__ . '/' .$this->ns . '/input.php';
    }

    protected function readTextInput(): void
    {
        $content = file_get_contents(__DIR__ . '/' .$this->ns . '/input.php');

        $this->input = explode(PHP_EOL, $content);
    }
}
