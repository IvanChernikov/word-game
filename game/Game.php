<?php

namespace Word\Game;

class Game
{
    /** @var State */
    protected $state;

    /** @var bool */
    protected $exit;

    public function __construct()
    {
    }

    public function start()
    {
        $this->state = State::menu();
        $this->loop();
    }

    protected function loop()
    {
        while ($this->ok()) {
            $this->state = $this->state->execute($this->command());
        }
    }

    protected function ok()
    {
        return !$this->exit && empty($this->errors);
    }
}