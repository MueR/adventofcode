<?php

declare(strict_types=1);

namespace MueR\AdventOfCode;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use RuntimeException;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

abstract class AbstractSolver
{
    private Stopwatch $stopwatch;
    protected string|array $input;
    protected array $testInput;
    private string $ns;
    public int $day;

    public function __construct(protected bool $test = false)
    {
        $this->ns = str_replace('\\', '/', substr(get_class($this), strlen('MueR\\AdventOfCode\\'), -5));
        $this->day = (int)substr($this->ns, -2);
        $this->stopwatch = new Stopwatch(true);
        $this->stopwatch->start($this->ns);

        $this->parse();
    }

    abstract public function partOne(): int|float;
    abstract public function partTwo(): int|float;

    public function lap(): StopwatchEvent
    {
        return $this->stopwatch->lap($this->ns);
    }

    public function stop(): StopwatchEvent
    {
        return $this->stopwatch->stop($this->ns);
    }

    public function setTestmode(bool $testMode): self
    {
        $this->test = $testMode;

        return $this;
    }

    final protected function getInput(?int $index = null): array|string|int
    {
        if ($index !== null) {
            return $this->input[$index];
        }

        return $this->input;
    }

    protected function readPhpInput()
    {
        $file = __DIR__ . '/' .$this->ns . '/' . ($this->test ? 'test' : 'input') . '.php';
        if (!file_exists($file)) {
            throw new RuntimeException(sprintf('Input file "%s" does not exist.', $file));
        }

        return require $file;
    }

    protected function parse(): void
    {
        $this->input = explode(PHP_EOL, $this->readText());
    }

    protected function readText(): string
    {
        $file = __DIR__ . '/' .$this->ns . '/' . ($this->test ? 'test' : 'input') . '.txt';
        if (!file_exists($file)) {
            throw new RuntimeException(sprintf('Input file "%s" does not exist.', $file));
        }

        $content = file_get_contents($file);

        return trim($content);
    }
}
