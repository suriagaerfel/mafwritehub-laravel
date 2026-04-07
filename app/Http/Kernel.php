<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * Global HTTP middleware stack.
     *
     * These middleware run during **every request**.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
       
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
       
    ];

    /**
     * Route middleware aliases.
     *
     * These can be applied to routes individually.
     *
     * @var array<string, class-string|string>
     */
    protected $routeMiddleware = [
        'accountRecords' => \App\Http\Middleware\AccountRecordsMiddleware::class,
    ];
}