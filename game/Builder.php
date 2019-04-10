<?php

namespace Word\Game;

class Builder
{
    public static function extractBit($number, $k, $p)
    {
        return (((1 << $k) - 1) & ($number >> ($p - 1)));
    }
}