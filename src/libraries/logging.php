<?php

function logMessage(string $level, string $message, array $context = [])
{
    $errorLevels = ['error', 'critical', 'alert', 'emergency'];

    $stream = in_array(strtolower($level), $errorLevels) ? 'php://stderr' : 'php://stdout';

    $handle = fopen($stream, 'wb');
    if (!$handle) {
        throw new RuntimeException("Unable to open the stream for log level: $level");
    }

    $timeString = date('Y-m-d H:i:s');
    $contextString = !empty($context) ? json_encode($context) : '';

    $logMessage = sprintf("[%s] %s: %s %s\n", $timeString, strtoupper($level), $message, $contextString);

    fwrite($handle, $logMessage);
    fclose($handle);
}
