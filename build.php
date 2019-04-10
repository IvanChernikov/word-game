<?php

use Word\Game\Builder;

include_once('game/Builder.php');

// WRITE

$json = json_decode(file_get_contents('resource/board.json'));

$stream = fopen('resource/board.bin', 'wb');

$data = "";

foreach ($json->board as $row) {
    $bytes = "0";
    foreach ($row as $square) {
        $bytes .= sprintf("%'.03d", decbin($square));
        if (strlen($bytes) === 16) {
            $int   = bindec($bytes);
            $data  .= pack("n", $int);
            $bytes = "0";
        }
    }
}

fwrite($stream, $data);
fclose($stream);

// READ

$file = fopen('resource/board.bin', 'rb');
$raw  = fread($file, 240);
fclose($file);

$binary = unpack("n*", $raw);

$board = [];
$index = 0;
foreach ($binary as $set) {
    $cells = str_split(substr(sprintf("%'.016s", decbin($set)), 1), 3);
    foreach ($cells as $cell) {
        $int = bindec($cell);
        $key = intdiv($index, 15);
        if (!key_exists($key, $board)) {
            $board[$key] = [];
        }
        $board[$key][] = $int;
        $index++;
    }
}

// TEST

$validation = "";

for ($r = 0; $r < 15; $r++) {
    for ($c = 0; $c < 15; $c++) {
        $validation .= ' ' . ($board[$r][$c] === $json->board[$r][$c] ? $board[$r][$c] : 'X') . ' ';
    }
    $validation .= PHP_EOL;
}

echo "\n$validation\n";