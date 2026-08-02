<?php

return [

    'default' => env('FILESYSTEM_DISK', 'local'),

    'disks' => [

        // 🟤 التخزين المحلي (للملفات المؤقتة والنسخ الاحتياطي)
        'local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
            'throw' => false,
        ],

        // 🟤 نسخة محلية احتياطية للملفات العامة (يمكن استخدامها للاختبار)
        'local_public' => [
            'driver' => 'local',
            'root' => storage_path('app/public'),
            'url' => env('APP_URL') . '/storage',
            'visibility' => 'public',
            'throw' => false,
        ],

        // 🟤 نسخة محلية احتياطية للملفات الخاصة
        'local_private' => [
            'driver' => 'local',
            'root' => storage_path('app/private'),
            'visibility' => 'private',
            'throw' => false,
        ],

        // 🟤 المستندات الآمنة (محلياً)
        'secure_documents' => [
            'driver' => 'local',
            'root' => storage_path('app/private/documents'),
            'visibility' => 'private',
            'throw' => false,
            'permissions' => [
                'file' => [
                    'public' => 0640,
                    'private' => 0600,
                ],
                'dir' => [
                    'public' => 0750,
                    'private' => 0700,
                ],
            ],
        ],

        // 🔵 التخزين السحابي عبر Backblaze B2 (S3-compatible)
        'b2' => [
            'driver' => 's3',
            'key' => env('BACKBLAZE_KEY_ID'),
            'secret' => env('BACKBLAZE_APPLICATION_KEY'),
            'region' => env('BACKBLAZE_REGION', 'eu-central-003'),
            'bucket' => env('BACKBLAZE_BUCKET'),
            'endpoint' => env('BACKBLAZE_ENDPOINT', 'https://s3.eu-central-003.backblazeb2.com'),
            'use_path_style_endpoint' => true,
            'visibility' => 'private',
            'throw' => true,
        ],

        // 🟢 القرص العام (يستخدم في الكود الحالي عبر Storage::disk('public'))
        'public' => [
            'driver' => 's3',
            'key' => env('BACKBLAZE_KEY_ID'),
            'secret' => env('BACKBLAZE_APPLICATION_KEY'),
            'region' => env('BACKBLAZE_REGION', 'eu-central-003'),
            'bucket' => env('BACKBLAZE_BUCKET'),
            'endpoint' => env('BACKBLAZE_ENDPOINT', 'https://s3.eu-central-003.backblazeb2.com'),
            'use_path_style_endpoint' => true,
            'prefix' => 'public', // 👈 كل الملفات في هذا المجلد داخل الـ Bucket
            'visibility' => 'private',
            'throw' => true,
        ],

        // 🔒 القرص الخاص (يستخدم في الكود الحالي عبر Storage::disk('private'))
        'private' => [
            'driver' => 's3',
            'key' => env('BACKBLAZE_KEY_ID'),
            'secret' => env('BACKBLAZE_APPLICATION_KEY'),
            'region' => env('BACKBLAZE_REGION', 'eu-central-003'),
            'bucket' => env('BACKBLAZE_BUCKET'),
            'endpoint' => env('BACKBLAZE_ENDPOINT', 'https://s3.eu-central-003.backblazeb2.com'),
            'use_path_style_endpoint' => true,
            'prefix' => 'private', // 👈 كل الملفات في هذا المجلد داخل الـ Bucket
            'visibility' => 'private',
            'throw' => true,
        ],

        // 🟦 AWS S3 (إذا كنت تستخدمه في أي مكان آخر، اتركه كما هو)
        's3' => [
            'driver' => 's3',
            'key' => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
            'region' => env('AWS_DEFAULT_REGION'),
            'bucket' => env('AWS_BUCKET'),
            'url' => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
            'use_path_style_endpoint' => env('AWS_USE_PATH_STYLE_ENDPOINT', false),
            'throw' => false,
        ],

    ],

    'links' => [
        public_path('storage') => storage_path('app/public'),
    ],

];
