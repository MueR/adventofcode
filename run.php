<?php

declare(strict_types=1);

use MueR\AdventOfCode\AbstractSolver;

require_once __DIR__ . '/vendor/autoload.php';

$arguments = $argv;

$informationArray = [];

$options = getopt('d::y::');
$days = array_key_exists('d', $options) ? [(int)$options['d']] : range(1, 25);
$year = array_key_exists('y', $options) ? (int)$options['y'] : (int)date('Y');

foreach ($days as $day) {
    $dayData = printDay($day, $year);
    if (null !== $dayData) {
        $informationArray[] = $dayData;
    }
}

function printDay(int $day, int $year = 2021): array|null
{
    $class = sprintf('MueR\\AdventOfCode\\AdventOfCode%04d\\Day%02d\\Day%02d', $year, $day, $day);
    $dayString = sprintf('%02d', $day);

    if (!class_exists($class)) {
        return null;
    }

    try {
        /** @var AbstractSolver $instance */
        $instance = new $class();
        $instance->lap();
    } catch (RuntimeException $e) {
        echo 'Skipped day because "' . $e->getMessage() . '"' . PHP_EOL;

        return [
            'day' => $dayString,
            'partOneSolution' => null,
            'partTwoSolution' => null,
        ];
    }

    try {
        $partOneSolution = $instance->partOne();
        $instance->lap();
    } catch (RuntimeException $e) {
        $partOneSolution = 'Skipped part 1 because "' . $e->getMessage() . '"';
        echo $partOneSolution . PHP_EOL;
    }

    try {
        $partTwoSolution = $instance->partTwo();
    } catch (RuntimeException $e) {
        $partTwoSolution = 'Skipped part 2 because "' . $e->getMessage() . '"';
        echo $partTwoSolution . PHP_EOL;
    }

    return [
        'day' => $dayString,
        'partOneSolution' => $partOneSolution,
        'partTwoSolution' => $partTwoSolution,
        'time' => $instance->stop(),
    ];
}

printf("|%'-27s Advent Of Code %4d %'-26s|\n", '', $year, '');
printf("| Day | %-20s | %-20s | %-20s |\n", 'Solution 1', 'Solution 2', 'Diagnostics');
printf("|-----|%1\$s|%1\$s|%1\$s|\n", str_repeat('-', 22));
foreach ($informationArray as $information) {
    printf(
        "| %3d | %20d | %20d | %6.2F MiB - %4d ms |\n",
        $information['day'],
        $information['partOneSolution'] ?? -1,
        $information['partTwoSolution'] ?? -1,
        $information['time']?->getMemory() / 1024 / 1024,
        $information['time']?->getDuration()

    );
}
printf("|-----|%1\$s|%1\$s|%1\$s|\n", str_repeat('-', 22));
