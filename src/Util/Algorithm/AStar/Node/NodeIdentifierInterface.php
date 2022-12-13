<?php

namespace MueR\AdventOfCode\Util\Algorithm\AStar\Node;

interface NodeIdentifierInterface
{
    public function getUniqueNodeId(): string;
}
