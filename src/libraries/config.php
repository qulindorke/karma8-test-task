<?php

function config(string $name, mixed $default = null): mixed
{
    if (empty($name)) {
        throw new InvalidArgumentException("Configuration name cannot be empty.");
    }

    $path = explode('.', $name);
    $configFileName = array_shift($path);
    $configFilePath = "./config/{$configFileName}.php";

    if (!file_exists($configFilePath)) {
        throw new RuntimeException("Configuration file '{$configFileName}.php' not found.");
    }

    $config = require $configFilePath;

    return _getConfigValue($path, $config, $default);
}

function _getConfigValue(array $path, array $config, mixed $default = null): mixed
{
    $key = array_shift($path);

    if (!array_key_exists($key, $config)) {
        return $default;
    }

    $value = $config[$key];

    if (!empty($path)) {
        if (is_array($value)) {
            return _getConfigValue($path, $value, $default);
        }
        return $default;
    }

    return $value;
}
