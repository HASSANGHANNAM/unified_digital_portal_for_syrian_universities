<?php

return [
    'chunk_size' => (int) env('NOTIFICATION_BROADCAST_CHUNK_SIZE', 100),
    'max_recipients' => (int) env('NOTIFICATION_BROADCAST_MAX_RECIPIENTS', 0),
];
