<?php

function enqueueJob($connection, string $actionName, ...$parameters): void
{
    $data = [
        'action_name' => $actionName,
        'parameters' => json_encode($parameters),
        'status' => 'pending',
        'created_at' => date('Y-m-d H:i:s'),
    ];

    pg_insert($connection, 'job_queue', $data);

    logMessage('debug', 'Job has been queued', [
        'data' => $data
    ]);
}

function dequeueJob($connection): ?array
{
    // Start the transaction
    pg_query($connection, "BEGIN");

    // Lock the oldest pending job for update and mark it as processing
    $result = pg_query(
        $connection,
        "UPDATE job_queue SET status = 'processing', started_at = NOW() WHERE id = (
            SELECT id FROM job_queue WHERE status in ('pending', 'failed') ORDER BY created_at ASC FOR UPDATE SKIP LOCKED LIMIT 1
        ) RETURNING *"
    );

    // Check if any row was affected/updated
    if (pg_affected_rows($result) === 0) {
        // Rollback the transaction if no job is updated
        pg_query($connection, "ROLLBACK");
        return null;
    }

    // Commit the transaction to finalize the job status update
    pg_query($connection, "COMMIT");

    // Fetch and return the job data
    return pg_fetch_assoc($result);
}


function resetStuckJobs($connection): void
{
    $interval = '5 minutes';
    $query = "UPDATE job_queue SET status = 'pending', started_at = NULL WHERE status = 'processing' AND started_at < NOW() - INTERVAL '$interval'";
    pg_query($connection, $query);
}

function finishJob($connection, int $jobId): void
{
    $conditions = ['id' => $jobId];
    pg_delete($connection, 'job_queue', $conditions);
}

function failJob($connection, int $jobId): void
{
    $data = ['status' => 'failed'];
    $condition = ['id' => $jobId];

    pg_update($connection, 'job_queue', $data, $condition);
}
