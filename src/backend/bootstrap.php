<?php

require_once dirname(__DIR__, 2) . '/vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(dirname(__DIR__, 2)))->bootstrap();
date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

$app = new Laravel\Lumen\Application(dirname(__DIR__, 2));
$app->useStoragePath(dirname(__DIR__, 2) . '/var');

$app->withFacades();
$app->withEloquent();

$app->configure('app');
$app->configure('database');
$app->configure('view');
$app->configure('swagger-lume');

$app->configure('lang/ru/message');
$app->configure('lang/ru/message_validation');

$app->configure('lang/ua/message');
$app->configure('lang/ua/message_validation');

$app->singleton(Illuminate\Contracts\Debug\ExceptionHandler::class, Kronas\Api\Handler::class);
$app->singleton(Illuminate\Contracts\Console\Kernel::class, Laravel\Lumen\Console\Kernel::class);

$app->register(Kronas\Api\Customer\Services\Dsp\DspProvider::class);
$app->register(SwaggerLume\ServiceProvider::class);
$app->register('Nord\Lumen\Cors\CorsServiceProvider');

$app->middleware([
    'Nord\Lumen\Cors\CorsMiddleware'
]);

$app->router->group(['namespace' => 'Kronas'], fn($router) => require __DIR__ . '/routes/routes.php');

return $app;