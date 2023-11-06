<?php

function invalidateUserEmail($connection, int $userId): void
{
    logMessage('debug', 'User\'s email has been invalidated', [
        'user_id' => $userId
    ]);

    $updateQuery = "UPDATE users SET valid = FALSE, checked = TRUE WHERE id = $1";

    pg_prepare($connection, "", $updateQuery);
    pg_execute($connection, "", [$userId]);
}
