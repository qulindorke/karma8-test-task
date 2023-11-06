<?php

function sendEmail(string $from, string $to, string $text): bool
{
    sleep(rand(1, 10));

    logMessage("info", "Email has been sent", [
        'from' => $from,
        'to' => $to,
        'text' => $text
    ]);

    return true;
}

function checkEmail(string $email): bool
{
    sleep(rand(1, 60));

    $result = (bool)rand(0, 1);

    logMessage('info', 'Email has been checked with external service', [
        'email' => $email,
        'result' => $result
    ]);

    return $result;
}

function validateEmail(string $email): bool
{
    $pattern = "/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/";
    return preg_match($pattern, $email) === 1;
}
