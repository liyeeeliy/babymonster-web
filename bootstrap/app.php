<?php

use App\Http\Middleware\EnsureUserHasRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Proxy Railway menerima HTTPS lalu meneruskannya ke aplikasi sebagai HTTP.
        // Tanpa ini Laravel mengira pengunjung memakai HTTP dan menulis URL aset
        // sebagai http://, yang diblokir browser di halaman https (mixed content).
        $middleware->trustProxies(at: '*');

        // Alias middleware untuk proteksi berbasis peran: ->middleware('role:admin,staff')
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
