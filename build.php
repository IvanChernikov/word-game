<?php

$json = json_decode(file_get_contents('resource/board.json'));

//$stream = fopen('resource/board.dat', 'wb');

$hex = [];

foreach ($json->board as $row) {
    $bin   = 0;
    $count = 0;
    foreach ($row as $square) {
        $offset = ($count * 3);
        $bin |= $square << $offset;
        echo "$offset => $bin\n";
        if ($count === 4) {
            $hex[] = bin2hex($bin);
            $bin   = 0;
            $count = 0;
        }
        $count++;
    }
}

//fclose($stream);
//print_r($hex);