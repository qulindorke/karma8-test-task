<?php

return static function (): int {
    $connection = getPgSqlConnection();

    $currentTimestamp = strtotime('tomorrow 00:00:00');
    $tomorrowEndTimestamp = strtotime('tomorrow 23:59:59');
    $threeDaysAfterTomorrowStartTimestamp = strtotime('+3 days 00:00:00');
    $threeDaysAfterTomorrowEndTimestamp = strtotime('+3 days 23:59:59');

    $select = "SELECT * FROM users WHERE confirmed IS TRUE AND (
        (validts > $currentTimestamp AND validts <= $tomorrowEndTimestamp) OR
        (validts > $threeDaysAfterTomorrowStartTimestamp AND validts <= $threeDaysAfterTomorrowEndTimestamp)
    )";

    $result = pg_query($connection, $select);
    if (!$result) {
        exitWithMessage('An error occurred when making query to db');
    }

    $usersWithExpiringSubscriptions = pg_fetch_all($result);

    logMessage('debug', 'Found ' . count($usersWithExpiringSubscriptions) . ' users with expiring subscription');

    foreach ($usersWithExpiringSubscriptions as $user) {
        $userId = $user['id'];
        $username = $user['username'];
        $email = $user['email'];
        $checked = $user['checked'] === 't';
        $valid = $user['valid'] === 't';

        if (!$checked) {
            enqueueJob($connection, 'check-email-and-send-notification', $userId, $username, $email);
        } elseif ($checked && $valid) {
            enqueueJob($connection, 'send-notification', $username, $email);
        }
    }

    return SUCCESS_EXIT_CODE;
};
