<?php
return [
    // Redis key 前缀
    'key_prefix' => env('REDIS_LOGGER_KEY_PREFIX', 'laravel:logs'),

    // 日志专用 Redis 配置
    'host'     => env('REDIS_LOGGER_HOST', '127.0.0.1'),
    'port'     => env('REDIS_LOGGER_PORT', 6379),
    'password' => env('REDIS_LOGGER_PASSWORD', null),
    'database' => env('REDIS_LOGGER_DB', 1),
];
