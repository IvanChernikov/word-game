<?php

namespace Word\Game\State;

use Word\Game\Command;
use Word\Game\StateHandler;

class Menu implements StateHandler
{

    public function run(): bool
    {

    }

    public function pass(Command $command): StateHandler
    {
        // TODO: Implement pass() method.
    }
}