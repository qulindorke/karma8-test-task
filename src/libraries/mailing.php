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
