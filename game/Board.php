<?php

namespace Word\Game;

class Board
{
    const BINARY_DEFAULT = 'resource/board.bin';
    private $tiles;
    private $letters;

    public function __construct(array $tiles, array $letters = [])
    {
        $this->tiles   = $tiles;
        $this->letters = $letters;
    }

    public static function fromBinary(string $path = self::BINARY_DEFAULT)
    {
        $stream = fopen($path, 'rb');
        $raw    = fread($stream, 240);
        fclose($stream);

        $binary = unpack("n*", $raw);

        $tiles = [];
        $index = 0;
        foreach ($binary as $set) {
            $cells = str_split(substr(sprintf("%'.016s", decbin($set)), 1), 3);
            foreach ($cells as $cell) {
                $int = bindec($cell);
                $key = intdiv($index, 15);
                if (!key_exists($key, $tiles)) {
                    $tiles[$key] = [];
                }
                $tiles[$key][] = $int;
                $index++;
            }
        }

        return new static($tiles);
    }

    public static function fromBinaryBitShift(string $path = self::BINARY_DEFAULT)
    {
        $stream = fopen($path, 'rb');
        $tiles  = [];
        $count  = 0;
        $mask   = ((1 << 3) - 1);
        while ($data = fread($stream, 2)) {
            $ray = unpack('n', $data);
            $i   = intdiv($count, 15);
            for ($p = 4; $p >= 0; $p--) {
                $tiles[$i][] = ($ray[1] >> ($p * 3)) & $mask;
                $count++;
            }
        }
        fclose($stream);
        return new static($tiles);
    }

    public static function fromArray(array $array)
    {
        return new static($array);
    }

    public function tile(int $x, int $y)
    {
        if ($x < 0 || $x >= 15 || $y < 0 || $y >= 15) {
            throw new \OutOfBoundsException("Tile ($x, $y) is out of bounds.");
        }

        return $this->tiles[$y][$x];
    }

    public function build($path = self::BINARY_DEFAULT)
    {
        $stream = fopen($path, 'wb');

        $data = "";

        foreach ($this->tiles as $row) {
            $bytes = "0";
            foreach ($row as $square) {
                $bytes .= sprintf("%'.03d", decbin($square));
                if (strlen($bytes) === 16) {
                    $data  .= pack("n", bindec($bytes));
                    $bytes = "0";
                }
            }
        }

        fwrite($stream, $data);
        fclose($stream);
    }
}