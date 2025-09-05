<?php
namespace Ccily2701\LaravelRedisLogger;

use Monolog\Handler\AbstractProcessingHandler;
use Illuminate\Support\Facades\Redis;

class RedisLogHandler extends AbstractProcessingHandler
{
    protected function write(array $record): void
    {
        $project = config('app.name', env('APP_NAME', 'default_project'));
        $key = config('redis-logger.key_prefix', 'laravel:logs') . ':' . $project;

        $config = config('redis-logger');

        // 独立 Redis 连接
        $redis = new \Redis();
        $redis->connect($config['host'], $config['port']);
        if (!empty($config['password'])) {
            $redis->auth($config['password']);
        }
        $redis->select($config['database']);

        $redis->rpush($key, json_encode([
            'message'  => $record['message'],
            'context'  => $record['context'],
            'level'    => $record['level_name'],
            'channel'  => $record['channel'],
            'datetime' => $record['datetime']->format('Y-m-d H:i:s'),
            'project'  => $project,
        ]));

        $redis->close();
    }
}
