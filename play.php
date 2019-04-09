<?php
/** @var Composer\Autoload\ClassLoader $loader */
$loader = include_once('vendor/autoload.php');

$game = new Game();

$game->start();