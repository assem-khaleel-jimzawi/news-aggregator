<?php

use Illuminate\Foundation\Application;
use Illuminate\Console\Scheduling\Schedule;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (): void {
        //
    })
    ->withExceptions(function (): void {
        //
    })
    ->withCommands([
        App\Console\Commands\FetchArticles::class, // Register your custom Artisan command
    ])
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('news:fetch')->hourly(); // Run the command hourly
    })
    ->create();
