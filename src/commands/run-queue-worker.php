<?php

return static function () {
    $connection = getPgSqlConnection();

    $sleepTime = 3;

    while (true) {
        $job = dequeueJob($connection);

        if (!$job) {
            logMessage('debug', "Empty queue. Sleeping for $sleepTime sec.");
            sleep($sleepTime);
            continue;
        }

        $actionName = $job['action_name'];
        $parameters = json_decode($job['parameters']);

        logMessage('debug', 'Running job', [
            'action_name' => $actionName,
            'parameters' => $parameters
        ]);

        runAction($actionName, ...$parameters);
    }
    // todo add pcntl exit and connection closing
};
