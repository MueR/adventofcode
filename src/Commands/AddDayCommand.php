<?php

namespace MueR\AdventOfCode\Commands;

use Laminas\Code\DeclareStatement;
use Laminas\Code\Generator\AbstractMemberGenerator;
use Laminas\Code\Generator\ClassGenerator;
use Laminas\Code\Generator\DocBlock\Tag\GenericTag;
use Laminas\Code\Generator\DocBlock\Tag\PropertyTag;
use Laminas\Code\Generator\DocBlockGenerator;
use Laminas\Code\Generator\FileGenerator;
use Laminas\Code\Generator\MethodGenerator;
use MueR\AdventOfCode\AbstractSolver;
use MueR\AdventOfCode\AdventOfCode;
use MueR\AdventOfCode\Generators\TypedPropertyGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AddDayCommand extends Command
{
    protected static $defaultName = 'add:day';
    private string $docBlockUrl;
    private ClassGenerator $generator;

    protected function configure(): void
    {
        $this
            ->setDescription('Add day class')
            ->addOption('day', 'd', mode: InputOption::VALUE_REQUIRED)
            ->addOption('year', 'y', mode: InputOption::VALUE_REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $day = $input->getOption('day');
        $year = $input->getOption('year');
        if ($day < 1 || $day > 25) {
            $output->writeln(sprintf(
                '<error>Expected a positive integer in the range of 1 to 25, got %d.</error>',
                $day,
            ));

            return Command::FAILURE;
        }

        $year = $year ?? (int) date('Y');
        $class = sprintf(AdventOfCode::NAMESPACE_TEMPLATE, $year, $day, $day);
        $fileName = dirname(__DIR__) . '/' .
            str_replace('\\', '/', substr($class, strlen('MueR\\AdventOfCode\\'))) .
            '.php'
        ;

        if (!is_dir(dirname($fileName))) {
            if (
                !mkdir($concurrentDirectory = dirname($fileName), 0755, recursive: true) &&
                !is_dir($concurrentDirectory)
            ) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $concurrentDirectory));
            }
        }

        if (class_exists($class)) {
            $output->writeln('Class ' . $class . ' already exists, skipping');
        } else {
            $this->docBlockUrl = sprintf('https://adventofcode.com/%d/day/%d', $year, $day);

            $this->generateClass($class, $day);
            $this->generateMethod(1);
            $this->generateMethod(2);

            $fileGenerator = new FileGenerator();
            $fileGenerator
                ->setDeclares([DeclareStatement::strictTypes(1)])
                ->setDocBlock((new DocBlockGenerator('Part of AdventOfCode ' . $year)))
                ->setClass($this->generator)
                ->setNamespace(substr($class, 0, strrpos($class, '\\')))
                ->setUses([AbstractSolver::class])
            ;

            $fileContent = $fileGenerator->generate();

            if (!file_put_contents($fileName, $fileContent)) {
                $output->writeln(sprintf('<error>Could not write file %s.</error>', $fileName));

                return Command::FAILURE;
            }

            $output->writeln(sprintf('<fg=green>✔</> Class %s created.', $fileName));
        }

        $this->createInputFiles($fileName);

        return Command::SUCCESS;
    }

    private function generateClass(string $className, int $day): void
    {
        $this->generator = new ClassGenerator();
        $this->generator
            ->setName(substr($className, strrpos($className, '\\') + 1))
            ->setExtendedClass('AbstractSolver')
            ->removeConstant('INPUT_MODE_PHP')
            ->removeConstant('INPUT_MODE_TEXT')
        ;

        if (!$this->generator->hasProperty('testInput')) {
            $prop = new TypedPropertyGenerator();
            $prop
                ->setName('testInput')
                ->setDefaultValue([])
                ->setFlags(AbstractMemberGenerator::FLAG_PROTECTED)
            ;
        }

        $docBlock = new DocBlockGenerator("Day $day puzzle.");
        $docBlock->setTag(new PropertyTag('input', 'array{int}'));
        $docBlock->setTag(new GenericTag('see', $this->docBlockUrl));
        $this->generator->setDocBlock($docBlock);
    }

    private function generateMethod(int $part): void
    {
        $methodName = 'part' . match ($part) {
            1 => 'One',
            2 => 'Two',
            default => throw new \InvalidArgumentException('Questions are only two part.'),
        };
        if ($this->generator->hasMethod($methodName)) {
            return;
        }

        $method = new MethodGenerator();
        $method
            ->setName($methodName)
            ->setReturnType('int')
            ->setVisibility(AbstractMemberGenerator::VISIBILITY_PUBLIC)
            ->setBody('return -1;')
        ;

        $methodDocBlock = new DocBlockGenerator();
        $methodDocBlock->setShortDescription(sprintf(
            'Solver method for part %d of the puzzle.',
            $part
        ));
        $methodDocBlock->setTag(new GenericTag(
            'see',
            $this->docBlockUrl . ($methodName === 'partTwo' ? '#part2' : '')
        ));
        $method->setDocBlock($methodDocBlock);

        $this->generator->addMethods([$method]);
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
