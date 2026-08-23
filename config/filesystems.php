<?php

return [

    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [

        // 🔵 التخزين المحلي الأساسي (جميع الملفات المؤقتة والعامة والخاصة)
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        // 🟢 الملفات العامة (التي يمكن الوصول إليها عبر الرابط)
        'public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        // 🔒 الملفات الخاصة (غير قابلة للوصول المباشر عبر الرابط)
        'private' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'visibility' => 'private',
            'throw' => false,
        ],

    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
