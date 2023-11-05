<?php

logMessage('debug', 'Schedule worker has been started');

$tasks = config('scheduler.tasks', []);

while (true) {
    foreach ($tasks as $task) {
        $commandName = $task[0] ?? null;
        if (empty($commandName)) {
            throw new UnexpectedValueException("Invalid task configuration: Command name is required.");
        }

        $period = $task[1] ?? null;
        if (empty($period)) {
            throw new UnexpectedValueException('Invalid task configuration: Execution frequency period is required.');
        }

        switch ($period) {
            case 'daily':
                _handleDailyFrequencyPeriod(commandName: $commandName, time: $task[2] ?? '00:00');
                break;
            case 'every minute':
                _handleEveryMinuteFrequencyPeriod($commandName);
                break;
            default:
                throw new UnexpectedValueException(
                    "Invalid task configuration: The specified frequency period '{$period}' is not supported."
                );
        }
    }

    sleep(60);
}

function _handleDailyFrequencyPeriod(string $commandName, string $time): void
{
    if (date('H:i') === $time) {
        runCommand($commandName);
    }
}

function _handleEveryMinuteFrequencyPeriod(string $commandName): void
{
    runCommand($commandName);
}
