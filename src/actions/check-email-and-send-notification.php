<?php

return function (int $userId, string $username, string $email): void {
    $connection = getPgSqlConnection();

    if (!validateEmail($email)) {
        logMessage('debug', 'Email has n\'t passed internal check');
        invalidateUserEmail($connection, $userId);
        return;
    }

    $checkingResult = checkEmail($email);

    if (!$checkingResult) {
        logMessage('debug', 'Email has n\'t passed external check');
        invalidateUserEmail($connection, $userId);
        return;
    }

    enqueueJob($connection, 'send-notification', $username, $email);
};
