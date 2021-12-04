<?php

declare(strict_types=1);

namespace MueR\AdventOfCode;

use InvalidArgumentException;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Stopwatch\Stopwatch;
use Symfony\Component\Stopwatch\StopwatchEvent;

abstract class AbstractSolver
{
    protected const INPUT_MODE_PHP = 'php';
    protected const INPUT_MODE_TEXT = 'text';

    private Stopwatch $stopwatch;
    protected string $inputMode = self::INPUT_MODE_TEXT;
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

        $this->input = match ($this->inputMode) {
            self::INPUT_MODE_PHP => $this->readPhpInput(),
            self::INPUT_MODE_TEXT => $this->readTextInput(),
            default => throw new InvalidArgumentException('Invalid input mode.'),
        };
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

    public function setTestmode(bool $testMode): self
    {
        $this->test = $testMode;

        return $this;
    }

    final protected function getInput(?int $index = null): array|string|int
    {
        if ($index !== null) {
            return $this->test ? $this->testInput[$index] : $this->input[$index];
        }

        return $this->test ? $this->testInput : $this->input;
    }

    protected function readPhpInput()
    {
        if (!file_exists(__DIR__ . '/' .$this->ns . '/input.php')) {
            return [];
        }

        return require __DIR__ . '/' .$this->ns . '/input.php';
    }

    #[Pure]
    protected function readTextInput(): array
    {
        return explode(PHP_EOL, $this->readText());
    }

    protected function readText(): string
    {
        if (!file_exists(__DIR__ . '/' .$this->ns . '/input.txt')) {
            return '';
        }

        $content = file_get_contents(__DIR__ . '/' .$this->ns . '/input.txt');

        return trim($content);
    }
}
