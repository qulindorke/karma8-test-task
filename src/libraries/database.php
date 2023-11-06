<?php

function getPgSqlConnection()
{
    $host = config('database.host');
    $port = config('database.port');
    $dbname = config('database.database');
    $user = config('database.username');
    $password = config('database.password');

    $connection = pg_connect("host={$host} port={$port} dbname={$dbname} user={$user} password={$password}");
    if (!$connection) {
        exitWithMessage("Error in connection: ".pg_last_error());
    }
    return $connection;
}
