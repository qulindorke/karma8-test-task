<?php

// Include Libraries
includeLibrary('array_helpers');
includeLibrary('env_helpers');
includeLibrary('logging');
includeLibrary('config');

function includeLibrary(string $libraryName): void
{
    $libFilePath = "./src/libraries/{$libraryName}.php";
    if (!file_exists($libFilePath)) {
        throw new RuntimeException(
            "The required library '{$libraryName}' cannot be loaded because the file does not exist."
        );
    }

    require_once $libFilePath;
}

function runCommand(string $commandName): mixed
{
    $commandFilePath = "./src/commands/{$commandName}.php";
    if (!file_exists($commandFilePath)) {
        throw new RuntimeException(
            "The required command '{$commandName}' cannot be loaded because the file does not exist."
        );
    }

    return require $commandFilePath;
}