<?php

return static function() {
    $connection = getPgSqlConnection();
    resetStuckJobs($connection);
    closePgSqlConnection($connection);
};
