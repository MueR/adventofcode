<?php

namespace MueR\AdventOfCode\Commands;

use Laminas\Code\DeclareStatement;
use Laminas\Code\Generator\AbstractMemberGenerator;
use Laminas\Code\Generator\ClassGenerator;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\FileGenerator;
use Laminas\Code\Generator\MethodGenerator;
use Laminas\Code\Generator\PropertyGenerator;
use Laminas\Code\Reflection\ClassReflection;
use MueR\AdventOfCode\AbstractSolver;
use MueR\AdventOfCode\AdventOfCode;
use MueR\AdventOfCode\Generators\TypedPropertyGenerator;
use ReflectionClass;
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
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $day = $input->getOption('day');
        $year = $input->getOption('year');
        if ($day < 1 || $day > 25) {
            $output->writeln(sprintf('<error>Expected a positive integer in the range of 1 to 25, got %d.</error>', $day));

            return Command::FAILURE;
        }

        $year = $year ?? (int)date('Y');
        $class = sprintf(AdventOfCode::NAMESPACE_TEMPLATE, $year, $day, $day);
        $filename = dirname(__DIR__) . '/' . str_replace('\\', '/', substr($class, strlen('MueR\\AdventOfCode\\'))) . '.php';

        if (class_exists($class)) {
            $output->writeln('Class ' . $class . ' already exists, skipping');
            $this->createInputFiles($filename);
            return Command::SUCCESS;
        }

        $generator = new ClassGenerator();
        $generator
            ->setName(substr($class, strrpos($class, '\\') + 1))
            ->setExtendedClass('AbstractSolver')
            ->removeConstant('INPUT_MODE_PHP')
            ->removeConstant('INPUT_MODE_TEXT')
        ;

        if (!$generator->hasProperty('testInput')) {
            $prop = new TypedPropertyGenerator();
            $prop
                ->setName('testInput')
                ->setDefaultValue([])
                ->setFlags(PropertyGenerator::FLAG_PROTECTED)
            ;
        }

        $docBlock = new DocBlockGenerator("Day $day puzzle.");
        $docBlock->setTag([
            'name' => 'property',
            'description' => 'array{int} $input',
        ]);
        $generator->setDocBlock($docBlock);

        foreach (['partOne', 'partTwo'] as $methodName) {
            if ($generator->hasMethod($methodName)) {
                continue;
            }
            $method = new MethodGenerator();
            $method
                ->setName($methodName)
                ->setReturnType('int')
                ->setVisibility(AbstractMemberGenerator::VISIBILITY_PUBLIC)
                ->setBody('return -1;')
            ;
            $generator->addMethods([$method]);
        }

        $fileGenerator = new FileGenerator();
        $fileGenerator
            ->setDeclares([DeclareStatement::strictTypes(1)])
            ->setClass($generator)
            ->setNamespace(substr($class, 0, strrpos($class, '\\')))
            ->setUses([AbstractSolver::class])
        ;

        $fileContent = $fileGenerator->generate();

        file_put_contents($filename, $fileContent);
        $output->writeln(sprintf('<fg=green>✔</> Class %s created.', $filename));
        $this->createInputFiles($filename);

        return Command::SUCCESS;
    }

    private function createInputFiles(string $className): void
    {
        $inputName = substr($className, 0, -9) . 'input.txt';
        if (!file_exists($inputName)) {
            touch($inputName);
        }
        $testInputName = substr($className, 0, -9) . 'test.txt';
        if (!file_exists($testInputName)) {
            touch($testInputName);
        }
    }
}
