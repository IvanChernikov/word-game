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
        $stream = fopen($path, 'rb'); // open stream to binary file
        $tiles  = []; // init board tile array
        $count  = 0; // init set iterations ( 3 sets per row )
        $mask   = ((1 << 3) - 1); // bit mask -> 111
        while ($data = fread($stream, 2)) { // read 2 bytes from stream
            $set = unpack('n', $data); // unpack the bytes as a short
            $idx = intdiv($count, 3); // get row index
            for ($pos = 0; $pos < 5; $pos++) { // iterate through the 5 sets of 3 bits
                // tile value = [ shift set of bits to start and extract the value ]
                $tiles[$idx][] = ($set[1] >> ($pos * 3)) & $mask;
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
            $bytes = [0, 0, 0]; // row represented as 3 sets of 2 bytes
            foreach ($row as $x => $tile) { // tile is an integer in range 0-7 so we need 3 bits to encode
                $set         = intdiv($x, 5); // get set index
                $offset      = ($x % 5) * 3; // get tile bits offset
                $bytes[$set] |= $tile << $offset; // insert bits at offset position
            }
            $data .= pack('nnn', ...$bytes); // pack encoded bytes
        }

        fwrite($stream, $data); // write encoded data
        fclose($stream);
    }
}