<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api.php'));

        Route::middleware('api')
            ->prefix('api')
            ->group(base_path('routes/api_v1.php'));

        Route::middleware('web')
            ->group(base_path('routes/web.php'));
    }
}
