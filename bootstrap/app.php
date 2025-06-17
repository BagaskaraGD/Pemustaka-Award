<?php
// bootstrap/app.php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'check.session' => \App\Http\Middleware\CheckSession::class,
            'check.mahasiswa' => \App\Http\Middleware\CheckMahasiswaSession::class,
            'check.dosen' => \App\Http\Middleware\CheckDosenSession::class,
            'period.status' => \App\Http\Middleware\CheckPeriodStatus::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // ...
    })->create();
