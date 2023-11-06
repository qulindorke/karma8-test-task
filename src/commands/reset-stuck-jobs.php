<?php

return static function(): int {
    $connection = getPgSqlConnection();
    resetStuckJobs($connection);

    return SUCCESS_EXIT_CODE;
};
