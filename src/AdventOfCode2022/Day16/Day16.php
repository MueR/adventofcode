<?php
/**
 * Part of AdventOfCode 2022
 */


declare(strict_types=1);

namespace MueR\AdventOfCode\AdventOfCode2022\Day16;

use Ds\Map;
use Illuminate\Support\Collection;
use MueR\AdventOfCode\AbstractSolver;

/**
 * Day 16 puzzle.
 *
 * @see https://adventofcode.com/2022/day/16
 */
class Day16 extends AbstractSolver
{
    private Collection $valves;
    private array $worthVisiting;

    /**
     * Solver method for part 1 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/16
     */
    public function partOne(): int
    {
        $steps = [['AA', 30, []]];
        $i = 0;

        do {
            [$i, $steps] = $this->openValves($i, $steps, $this->worthVisiting, $this->valves);
        } while ($i < count($steps));

        return (new Collection($steps))
            ->map(fn (array $step) => (new Collection($step[2]))->sum())
            ->sortDesc()
            ->first()
        ;
    }

    /**
     * Solver method for part 2 of the puzzle.
     *
     * @see https://adventofcode.com/2022/day/16#part2
     */
    public function partTwo(): int
    {
        $potentialValves = $this->valves->filter(fn (array $valve) => $valve[1] > 0)->all();
        $setToCheck = [[]];

        foreach ($potentialValves as $valve) {
            foreach ($setToCheck as $combination) {
                $setToCheck[] = array_merge([$valve[0]], $combination);
            }
        }

        return (new Collection($setToCheck))
            ->filter(fn (array $set) => count($set) === 7)
            ->map(fn (array $mine) => [$mine, array_diff(array_keys($potentialValves), $mine)])
            ->map(fn (array $pair) => $this->splitWork($pair[0], $pair[1], $this->worthVisiting, $this->valves))
            ->sortDesc()
            ->first() ?? -1
        ;
    }

    protected function parse(): void
    {
        $this->valves = (new Collection(explode("\n", $this->readText())))
            ->map(static function (string $line): array {
                preg_match(
                    '/Valve (\w+) has flow rate=(\d+); tunnels? leads? to valves? (.*)$/',
                    $line,
                    $matches
                );
                $neighbours = array_map('trim', explode(',', $matches[3]));
                return [$matches[1], (int)$matches[2], $neighbours];
            })
            ->mapWithKeys(fn (array $valve) => [$valve[0] => $valve])
        ;

        $this->worthVisiting = $this->valves
            ->mapWithKeys(fn (array $valve) => [
                $valve[0] => $this->distances($valve[0], $this->valves->all()),
            ])
            ->map(fn (array $distances) => (new Collection($distances))
                ->filter(fn (int $distance, string $valve) => $this->valves->get($valve)[1] > 0)
                ->all()
            )
            ->all()
        ;
    }

    private function distances(string $valve, array $valves): array
    {
        $steps = [[$valve, 0]];
        $i = 0;

        do {
            [$i, $steps] = $this->move($i, $steps, $valves);
            $seen = (new Collection($steps))
                ->unique(static fn (array $step) => $step[0])
                ->values()
                ->all()
            ;
        } while (count($seen) < count($valves));

        return (new Collection($steps))
            ->mapWithKeys(fn (array $step) => [$step[0] => $step[1]])
            ->all()
        ;
    }

    private function move(int $i, array $steps, array $valves): array
    {
        $nextSteps = $valves[($steps[$i][0])][2];

        $stepsToAdd = (new Collection($nextSteps))
            ->filter(fn (string $nextStep) => 0 === count(array_filter(
                    $steps,
                    static fn (array $seenStep) => $seenStep[0] === $nextStep
                )))
            ->map(fn (string $nextStep) => [$nextStep, $steps[$i][1] + 1])
            ->values()
            ->all()
        ;

        return [$i + 1, array_merge($steps, $stepsToAdd)];
    }

    private function openValves(
        int $i,
        array $steps,
        array $distancesWorthVisiting,
        Collection $valves,
        ?array $valvesForMe = null
    ): array {
        $step = $steps[$i];

        $nextValves = (new Collection($distancesWorthVisiting[$step[0]]))
            ->filter(static fn (int $distance, string $valve) => $step[1] - ($distance + 1) > 0)
            ->filter(static fn (int $distance, string $valve) => !isset($step[2][$valve]))
            ->filter(static function (int $distance, string $valve) use ($valvesForMe) {
                return $valvesForMe === null || in_array($valve, $valvesForMe, false);
            })
            ->map(static function (int $distance, string $valve) use ($step, $valves) {
                $timeRemaining = $step[1] - ($distance + 1);
                $openedValves = $step[2];
                $openedValves[$valve] = $timeRemaining * $valves->get($valve)[1];

                return [$valve, $timeRemaining, $openedValves];
            })
            ->values()
            ->all()
        ;

        return [$i + 1, array_merge($steps, $nextValves)];
    }

    private function splitWork(
        array $valvesForMe,
        array $valvesForElephant,
        array $distancesWorthVisiting,
        Collection $valves
    ): int {
        foreach ([$valvesForMe, $valvesForElephant] as $valvesForTarget) {
            $i = 0;
            $steps = [['AA', 26, []]];
            do {
                [$i, $steps] = $this->openValves(
                    $i,
                    $steps,
                    $distancesWorthVisiting,
                    $valves,
                    $valvesForTarget
                );
            } while ($i < count($steps));

            $best[] = (new Collection($steps))
                ->map(static fn (array $step) => (new Collection($step[2]))->sum())
                ->sortDesc()
                ->first();
        }

        return array_sum($best);
    }
}
