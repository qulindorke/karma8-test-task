<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Task Registration
    |--------------------------------------------------------------------------
    |
    | Here you may register tasks that should be executed periodically.
    | Format: array, where the first argument is the command name, and
    | the second argument is the execution frequency.
    |
    | Supported periods: 'daily', 'every minute'
    |
    | Example: ['my-command', 'every minute']
    */

    'tasks' => [
        ['reset-stuck-jobs', 'every minute'],
    ]
];
