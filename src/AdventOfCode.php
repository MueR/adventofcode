<?php

declare(strict_types=1);

namespace MueR\AdventOfCode;

use MueR\AdventOfCode\Commands\SolveCommand;
use Symfony\Component\Console\Application;

final class AdventOfCode extends Application
{
    public function __construct()
    {
        parent::__construct();

        $this->add(new SolveCommand());
        $this->setDefaultCommand('solve');
    }
}
