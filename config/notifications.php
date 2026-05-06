<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Sync vs queued bulk delivery
    |--------------------------------------------------------------------------
    |
    | Recipients count at or below this threshold are notified synchronously
    | (notifyNow / inline chunk processing). Above it, a queued job handles
    | chunking and batch inserts.
    |
    */
    'sync_recipient_threshold' => (int) env('NOTIFICATION_SYNC_THRESHOLD', 1),

    /*
    |--------------------------------------------------------------------------
    | Chunk size for bulk jobs and large sync batches
    |--------------------------------------------------------------------------
    */
    'chunk_size' => (int) env('NOTIFICATION_CHUNK_SIZE', 500),

];
