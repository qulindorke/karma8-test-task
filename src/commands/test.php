<?php

return static function () {
    $connection = getPgSqlConnection();

    enqueueJob($connection, 'send-subscription-expiration-notification', 'dima', 'dimon-camelot@yandex.ru');

    closePgSqlConnection($connection);
};
