<?php

return function (string $username, string $email) {
    $from = config('mailing.from');
    $text = "{$username}, your subscription is expiring soon";

    logMessage('debug', 'Attempt to send subscription expiration notification', [
        'from' => $from,
        'to' => $email,
        'text' => $text
    ]);

    sendEmail($from, $email, $text);
};
