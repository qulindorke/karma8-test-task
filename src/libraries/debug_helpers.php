<?php

function dump(...$args): void
{
    foreach ($args as $arg) {
        echo PHP_EOL;
        var_dump($arg);
    }
}

function dd(...$args): void
{
    dump(...$args);
    die();
}
