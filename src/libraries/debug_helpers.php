<?php

function dump(...$args)
{
    foreach ($args as $arg) {
        echo PHP_EOL;
        var_dump($arg);
    }
}

function dd(...$args)
{
    dump(...$args);
    die();
}
