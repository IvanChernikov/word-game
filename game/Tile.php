<?php

namespace Word\Game;

class Tile
{
    const TYPE_BLANK         = 0;
    const TYPE_ORIGIN        = 1;
    const TYPE_DOUBLE_LETTER = 2;
    const TYPE_TRIPLE_LETTER = 3;
    const TYPE_DOUBLE_WORD   = 4;
    const TYPE_TRIPLE_WORD   = 5;
    const TYPE_RESERVED_A    = 6;
    const TYPE_RESERVED_B    = 7;

    private $type = self::TYPE_BLANK;
    private $letter;

    public function __construct(int $type)
    {
        $this->type = $type;
    }

    public function place(Letter $letter)
    {
        $this->letter = $letter;
        return $this;
    }
}