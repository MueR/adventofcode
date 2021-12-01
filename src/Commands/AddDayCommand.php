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
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AddDayCommand extends Command
{
    protected static $defaultName = 'add:day';

    protected function configure(): void
    {
        $this
            ->setDescription('Add day class')
            ->addOption(
                name: 'day',
                shortcut: 'd',
                mode: InputOption::VALUE_REQUIRED,
            )
            ->addOption(
                name: 'year',
                shortcut: 'y',
                mode: InputOption::VALUE_REQUIRED,
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $day = $input->getOption('day');
        $year = $input->getOption('year');
        if ($day < 1 || $day > 25) {
            $output->writeln(sprintf('<error>Expected a positive integer in the range of 1 to 25, got %d.</error>', $day));

            return Command::FAILURE;
        }

        $year = $year ?? (int)date('Y');
        $class = sprintf(AdventOfCode::NAMESPACE_TEMPLATE, $year, $day, $day);

        $generator = new ClassGenerator();
        $generator
            ->setName(substr($class, strrpos($class, '\\') + 1))
            ->setExtendedClass('AbstractSolver');

        foreach (['partOne', 'partTwo'] as $methodName) {
            $method = new MethodGenerator();
            $method
                ->setName($methodName)
                ->setReturnType('int')
                ->setVisibility(AbstractMemberGenerator::VISIBILITY_PUBLIC)
                ->setBody('return -1;');
            $generator->addMethods([$method]);
        }

        $fileGenerator = new FileGenerator();
        $fileGenerator
            ->setDeclares([DeclareStatement::strictTypes(1)])
            ->setClass($generator)
            ->setNamespace(substr($class, 0, strrpos($class, '\\')))
            ->setUses([AbstractSolver::class]);

        $filename = dirname(__DIR__) . '/' . str_replace('\\', '/', substr($class, strlen('MueR\\AdventOfCode\\'))) . '.php';
        if (!file_exists($filename)) {
            file_put_contents($filename, $fileGenerator->generate());
        }
        $output->writeln('<fg=green>✔</> Class ' . $filename . ' created!');

        return Command::SUCCESS;
    }
}
