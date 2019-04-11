<?php

namespace Word\Game;

class Letter
{
    const VALUES = [
        1  => 'EAIONRTLSU',
        2  => 'DG',
        3  => 'BCMP',
        4  => 'FHVWY',
        5  => 'K',
        8  => 'JX',
        10 => 'QZ',
    ];

    private $char;
    private $value;

    public function __construct(string $char)
    {
        $this->char = $char;
        foreach (self::VALUES as $value => $characters) {
            if (in_array($char, $characters)) {
                $this->value = $value;
                break;
            }
        }
    }

    public function __toString()
    {
        return $this->char;
    }

    public function value()
    {
        return $this->value;
    }
}