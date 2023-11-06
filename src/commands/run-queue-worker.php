<?php

return static function () {
    $connection = getPgSqlConnection();

    $sleepTime = 3;

    while (true) {
        logMessage('debug', 'Trying to get new task from queue');

        if (pg_connection_status($connection) !== PGSQL_CONNECTION_OK) {
            pg_connection_reset($connection);
        }

        $job = dequeueJob($connection);

        if (!$job) {
            logMessage('debug', "Empty queue. Sleeping for $sleepTime sec.");
            sleep($sleepTime);
            continue;
        }

        $actionName = $job['action_name'];
        $parameters = json_decode($job['parameters']);

        logMessage('debug', 'Running job', [
            'job_id' => $job['id'],
            'action_name' => $actionName,
            'parameters' => $parameters
        ]);

        try {
            runAction($actionName, ...$parameters);
        } catch (Throwable $throwable) {
            failJob($connection, $job['id']);
        }

        finishJob($connection, $job['id']);
    }

    // @todo we should run job in processes to control execution time
};
