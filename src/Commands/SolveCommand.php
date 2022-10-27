<?php

namespace MueR\AdventOfCode\Commands;

use Error;
use Exception;
use MueR\AdventOfCode\AbstractSolver;
use MueR\AdventOfCode\AdventOfCode;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableCellStyle;
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

    private Table $table;

    protected function configure(): void
    {
        $this
            ->setDescription('Solve puzzles')
            ->addOption('year', 'y', mode: InputOption::VALUE_REQUIRED, default: (int)date('Y'))
            ->addOption('day', 'd', mode: InputOption::VALUE_REQUIRED)
            ->addOption('test', 't', mode: InputOption::VALUE_NONE)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->year = (int)$input->getOption('year');
        $days = $input->getOption('day') ? [(int)$input->getOption('day')] : range(1, 25);

        $formatter = $this->getHelper('formatter');
        $header = $formatter->formatBlock([
            '',
            sprintf('%42s%s%41s', ' ', 'Advent of Code ' . $this->year, ''),
            ''
        ], 'bg=green;options=bold');
        $output->write([
            "\n",
            $header,
            "\n\n",
        ]);

        if ($input->getOption('test')) {
            $output->writeln('Using test input.');
        }

        $this->table = new Table($output);
        $this->table
            ->setStyle('box-double')
            ->setHeaders(['Day', 'Solution 1', 'Solution 2', 'Memory', 'Runtime (p1)', 'Runtime (p2)'])
            ->setColumnWidths([3, 20, 20, 14, 14, 14]);

        foreach ($days as $day) {
            try {
                $this->solve($day, $output, (bool) $input->getOption('test'));
            } catch (Exception | Error $e) {
                $output->write($formatter->formatBlock([
                    sprintf(
                        '[%s] in %s on line %d:',
                        get_class($e),
                        sprintf(AdventOfCode::NAMESPACE_TEMPLATE, $this->year, $day, $day),
                        $e->getLine()
                    ),
                    $e->getMessage(),
                ], 'bg=red;options=bold') . "\n\n");
            }
        }

        $this->table->render();

        $output->writeln('');

        return Command::SUCCESS;
    }

    public function solve(int $day, OutputInterface $output, bool $test = false): void
    {
        $class = sprintf(AdventOfCode::NAMESPACE_TEMPLATE, $this->year, $day, $day);

        if (!class_exists($class)) {
            return;
        }

        /** @var AbstractSolver $instance */
        $instance = new $class($test);
        $instance->lap();

        $partOneSolution = $instance->partOne();
        $instance->lap();
        $partTwoSolution = $instance->partTwo();
        $instance->lap();

        $stopwatchData = $instance->stop();

        if ($partOneSolution === -1 && $partTwoSolution === -1) {
            return;
        }

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
                round($stopwatchData->getPeriods()[1]->getDuration(), 1) . ' ms',
                ['style' => new TableCellStyle(['align' => 'right'])]
            ),
            new TableCell(
                round($stopwatchData->getPeriods()[2]->getDuration(), 1) . ' ms',
                ['style' => new TableCellStyle(['align' => 'right'])]
            ),
        ]);
    }
}
