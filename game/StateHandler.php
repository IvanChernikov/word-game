<?php


namespace Word\Game;


interface StateHandler
{
    public function run(): bool;

    public function pass(Command $command): self;
}