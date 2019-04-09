<?php

$words = file_get_contents('resource/dictionary.txt');

$values = [
    1  => 'EAIONRTLSU',
    2  => 'DG',
    3  => 'BCMP',
    4  => 'FHVWY',
    5  => 'K',
    8  => 'JX',
    10 => 'QZ',
];

$map = [];
foreach ($values as $value => $letters) {
    foreach (str_split($letters) as $letter) {
        $map[$letter] = $value;
    }
}

list($script, $pattern) = $argv;

echo "Looking for pattern: {$pattern}\n\n";

preg_match_all("/(?:^|,)($pattern)(?:,|$)/", $words, $matches);

list($discard, $found) = $matches;
foreach ($found as $word) {
    $value = array_reduce(str_split($word), function ($carry, $letter) use ($map) {
        return $carry + $map[$letter];
    }, 0);

    echo sprintf("%4s => %s\n", $value, $word);
}