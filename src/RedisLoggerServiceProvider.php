<?php
namespace Ccily2701\LaravelRedisLogger;

use Illuminate\Support\ServiceProvider;
use Monolog\Logger;

class RedisLoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Laravel 6.x 自定义日志 channel
        $this->app->make('log')->extend('redis', function ($app, array $config) {
            return new Logger('redis', [
                new RedisLogHandler()
            ]);
        });

        // 发布配置文件
        $this->publishes([
            __DIR__ . '/../config/redis-logger.php' => config_path('redis-logger.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/redis-logger.php',
            'redis-logger'
        );
    }
}
