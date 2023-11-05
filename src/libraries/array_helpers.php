<?php

function arr(array $array, int|string $key, mixed $default = null): mixed
{
    return $array[$key] ?? $default;
}
