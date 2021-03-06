<?php

include_once('game/Board.php');

use Word\Game\Board;

function validate(array $expected, Board $board)
{
    $validation = "";

    for ($r = 0; $r < 15; $r++) {
        for ($c = 0; $c < 15; $c++) {
            $tile = $board->tile($c, $r);
            $validation .= ' ' . ($tile === $expected[$r][$c] ? $tile : 'X') . ' ';
        }
        $validation .= PHP_EOL;
    }

    echo "\n$validation\n";
}

function speed(int $iterations, callable $method)
{
    $time = microtime(true);
    for ($i = $iterations; $i >= 0; $i--) {
        $method();
    }
    $time = microtime(true) - $time;
    echo "Finished in {$time}s\n";
}

// WRITE

$json = json_decode(file_get_contents('resource/board-no-def.json'));

$board = Board::fromArray($json->board);
$board->build();

// TEST

/*
echo "Validate binary load:\n\n";
$board = Board::fromBinary();
validate($json->board, $board);
*/
echo "Validate binary bit-shift load:\n\n";
$board = Board::fromBinary();
validate($json->board, $board);

// SPEED

$iterations = 10000;
/*
echo "Test binary load (x$iterations):\n\n";
speed($iterations, function () {
    Board::fromBinary();
});

echo "Test binary bit-shift load (x$iterations):\n\n";
speed($iterations, function () {
    Board::fromBinaryBitShift();
});
*/
echo "Test build (x$iterations):\n\n";
speed($iterations, function () use ($board) {
    $board->build();
});