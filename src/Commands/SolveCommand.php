<?php

namespace MueR\AdventOfCode\Commands;

use MueR\AdventOfCode\AbstractSolver;
use MueR\AdventOfCode\AdventOfCode;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @method AdventOfCode getApplication()
 */
class SolveCommand extends Command
{
    protected static $defaultName = 'solve';
    private int $year;
    private array $days = [];
    private Table $table;

    protected function configure(): void
    {
        $this
            ->setDescription('Solve puzzles')
            ->addOption(
                name: 'year',
                shortcut: 'y',
                mode: InputOption::VALUE_REQUIRED,
                default: (int)date('Y')
            )
            ->addOption(
                name: 'day',
                shortcut: 'd',
                mode: InputOption::VALUE_REQUIRED,
                default: range(1, 25)
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->year = (int)$input->getOption('year');
        $this->days = $input->getOption('day');

        $formatter = $this->getHelper('formatter');
        $header = $formatter->formatBlock([
            '',
            sprintf('%30s%s%29s', ' ', 'Advent of Code ' . $this->year, ''),
            ''
        ], 'bg=green;options=bold');
        $output->write([
            "\n",
            $header,
            "\n\n",
        ]);

        $this->table = new Table($output);
        $this->table
            ->setStyle('box-double')
            ->setHeaders(['Day', 'Solution 1', 'Solution 2', 'Memory', 'Runtime'])
            ->setColumnWidths([4, 20, 20, 10, 10]);

        foreach ($this->days as $day) {
            try {
                $this->solve($day);
            } catch (\RuntimeException $e) {
                // Skip.
            }
        }

        $this->table->render();

        $output->writeln('');

        return Command::SUCCESS;
    }

    public function solve(int $day): void
    {
        $class = sprintf('MueR\\AdventOfCode\\AdventOfCode%04d\\Day%02d\\Day%02d', $this->year, $day, $day);

        if (!class_exists($class)) {
            return;
        }

        try {
            /** @var AbstractSolver $instance */
            $instance = new $class();
            $instance->lap();
        } catch (\RuntimeException $e) {
            echo 'Skipped day because "' . $e->getMessage() . '"' . PHP_EOL;

            return;
        }

        try {
            $partOneSolution = $instance->partOne();
            $instance->lap();
        } catch (\RuntimeException $e) {
            $partOneSolution = 'Skipped part 1 because "' . $e->getMessage() . '"';
            echo $partOneSolution . PHP_EOL;
        }

        try {
            $partTwoSolution = $instance->partTwo();
        } catch (\RuntimeException $e) {
            $partTwoSolution = 'Skipped part 2 because "' . $e->getMessage() . '"';
            echo $partTwoSolution . PHP_EOL;
        }

        $stopwatchData = $instance->stop();
        $this->table->addRow([
            new TableCell(
                $day,
                ['style' => new TableCellStyle(['align' => 'right'])]
            ),
            new TableCell(
                $partOneSolution ?? -1,
                ['style' => new TableCellStyle(['align' => 'right', 'fg' => $partOneSolution ? 'default' : 'red'])]
            ),
            new TableCell(
                $partTwoSolution ?? -1,
                ['style' => new TableCellStyle(['align' => 'right', 'fg' => $partTwoSolution ? 'default' : 'red'])]
            ),
            new TableCell(
                $stopwatchData->getMemory() / 1024 / 1024 . ' MiB',
                ['style' => new TableCellStyle(['align' => 'right'])]
            ),
            new TableCell(
                $stopwatchData->getDuration() . ' ms',
                ['style' => new TableCellStyle(['align' => 'right'])]
            ),
        ]);
    }
}
