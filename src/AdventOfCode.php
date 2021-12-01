<?php

declare(strict_types=1);

namespace MueR\AdventOfCode;

use MueR\AdventOfCode\Commands\AddDayCommand;
use MueR\AdventOfCode\Commands\AddYearCommand;
use MueR\AdventOfCode\Commands\SolveCommand;
use Symfony\Component\Console\Application;

final class AdventOfCode extends Application
{
    public const NAMESPACE_TEMPLATE = 'MueR\\AdventOfCode\\AdventOfCode%04d\\Day%02d\\Day%02d';

    public function __construct()
    {
        parent::__construct();

        $this->add(new SolveCommand());
        $this->add(new AddDayCommand());
        $this->add(new AddYearCommand());
        $this->setDefaultCommand('solve');
    }
}
