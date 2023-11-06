<?php

$libraries = [
    'debug_helpers',
    'env_helpers',
    'logging',
    'config',
    'database',
    'queue'
];

array_walk($libraries, 'includeLibrary');

/***********************************
 * CORE FUNCTIONS
 ***********************************/

function includeLibrary($libraryName): void
{
    $libFilePath = "./src/libraries/{$libraryName}.php";
    if (!file_exists($libFilePath)) {
        exitWithMessage("The required library '{$libraryName}' cannot be loaded because the file does not exist.");
    }

    require_once $libFilePath;
}

function loadAndRun($type, $name, $args = [])
{
    $filePath = "./src/{$type}/{$name}.php";
    if (!file_exists($filePath)) {
        exitWithMessage("The required {$type} '{$name}' cannot be loaded because the file does not exist.");
    }

    logMessage('debug', "Running '{$name}' {$type}", ['args' => $args]);

    $callable = require $filePath;

    return $callable(...$args);
}

function runCommand($commandName, ...$args)
{
    return loadAndRun('commands', $commandName, $args);
}

function runAction($actionName, ...$args)
{
    return loadAndRun('actions', $actionName, $args);
}

function exitWithMessage($message, $code = 1)
{
    echo $message.PHP_EOL;
    exit($code);
}
