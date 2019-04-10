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
        $stream = fopen($path, 'rb'); // open stream to binary file
        $tiles  = []; // init board tile array
        $count  = 0; // init set iterations ( 3 sets per row )
        $mask   = ((1 << 3) - 1); // bit mask -> 111
        while ($data = fread($stream, 2)) { // read 2 bytes from stream
            $ray = unpack('n', $data); // unpack the bytes as a short
            $idx = intdiv($count, 3); // set row index
            for ($pos = 4; $pos >= 0; $pos--) { // iterate through the 5 sets of 3 bits
                // tile value = [ shift set of bits to start and extract the value ]
                $tiles[$idx][] = ($ray[1] >> ($pos * 3)) & $mask;
            }
            $count++; // increment set
        }
        fclose($stream); // close stream
        return new static($tiles); // return Board object with loaded tiles
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