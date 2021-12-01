<?php

namespace MueR\AdventOfCode\Commands;

use Laminas\Code\DeclareStatement;
use Laminas\Code\Generator\AbstractMemberGenerator;
use Laminas\Code\Generator\ClassGenerator;
use Laminas\Code\Generator\FileGenerator;
use Laminas\Code\Generator\MethodGenerator;
use MueR\AdventOfCode\AbstractSolver;
use MueR\AdventOfCode\AdventOfCode;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AddYearCommand extends Command
{
    protected static $defaultName = 'add:year';

    protected function configure(): void
    {
        $this
            ->setDescription('Add year')
            ->addOption(
                name: 'year',
                shortcut: 'y',
                mode: InputOption::VALUE_REQUIRED,
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $year = (int)$input->getOption('year');
        $srcPath = dirname(__DIR__);

        $addDayCommand = $this->getApplication()->find('add:day');


        for ($i = 1; $i < 26; $i++) {
            $directory = sprintf('%s/AdventOfCode%4d/Day%02d', $srcPath, $year, $i);
            if (!@mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $directory));
            }

            $addDayCommand->run(new ArrayInput(['--day' => $i, '--year' => $year]), $output);
        }
        $output->writeln('');
        $output->writeln('<fg=green>✔</> Year ' . $year . ' created!');

        return Command::SUCCESS;
    }
}
