# Laravel Redis Logger

一个 Laravel 6 的日志扩展包，将日志写入独立 Redis（不影响项目原有缓存/队列），方便 Logstash/ES 采集。

## 安装

composer require ccily2701/laravel-redis-logger

## 发布配置文件

```
php artisan vendor:publish --provider="Ccily2701\LaravelRedisLogger\RedisLoggerServiceProvider" --tag="config"
```
发布后会在 config/redis-logger.php 生成配置文件，你可以修改日志 Redis 的参数：

REDIS_LOGGER_HOST=127.0.0.1
REDIS_LOGGER_PORT=6379
REDIS_LOGGER_PASSWORD=你的密码
REDIS_LOGGER_DB=1
REDIS_LOGGER_KEY_PREFIX=laravel:logs

- REDIS_LOGGER_DB 可以设置为独立数据库，避免影响应用缓存或队列
- REDIS_LOGGER_KEY_PREFIX 用于生成 Redis key，例如：laravel:logs:APP_NAME

## 修改 logging.php

在 config/logging.php 的 channels 数组中添加：
````
'channels' => [
    // 其他 channel...
    'redis' => [
        'driver'  => 'monolog',
        'level'   => 'debug',
        'handler' => \Ccily2701\LaravelRedisLogger\RedisLogHandler::class,
    ],
],
````
- 这里 handler 指向包自带的 RedisLogHandler

## 使用日志
````
use Illuminate\Support\Facades\Log;

Log::channel('redis')->info('这是一条测试日志');

Log::channel('redis')->error('用户登录失败', ['user_id' => 123, 'ip' => request()->ip()]);
````
- 日志会写入独立 Redis（默认 db=1）
- Redis key 格式：laravel:logs:{APP_NAME}
- Logstash 可直接消费该 key 写入 Elasticsearch
