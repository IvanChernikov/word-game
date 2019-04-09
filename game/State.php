<?php

namespace Word\Game;

class State
{
    const NAME_MENU  = 'menu';
    const NAME_BOARD = 'board';

    protected $name;
    protected $handler;

    public function __construct(string $name, StateHandler $handler)
    {
        $this->name    = $name;
        $this->handler = $handler;
    }

    /**
     * @return static
     */
    public static function menu()
    {
        return new static(static::NAME_MENU, null);
    }

    /**
     * @return static
     */
    public static function board()
    {
        return new static(static::NAME_BOARD, null);
    }

    /**
     * @param Command $command
     * @return bool
     */
    public function execute(Command $command)
    {
        return $this->handler->pass($command)->run();
    }
}