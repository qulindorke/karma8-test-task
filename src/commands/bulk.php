<?php

logMessage('debug', "Hello world!");
logMessage('debug', 'db config', [
    'database_config' => config('database')
]);
