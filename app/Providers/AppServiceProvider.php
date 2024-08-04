<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\V1\Interfaces\RecipientRepositoryInterface;
use App\Repositories\V1\Interfaces\MessageRepositoryInterface;
use Illuminate\Console\Scheduling\Schedule;
use App\Repositories\V1\RecipientRepository;
use App\Repositories\V1\MessageRepository;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Repositories
        $this->app->bind(RecipientRepositoryInterface::class, RecipientRepository::class);
        $this->app->bind(MessageRepositoryInterface::class, MessageRepository::class);

        // Commands
        // Register console commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \App\Console\Commands\V1\CleanExpiredMessages::class,
            ]);
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(Request $request): void
    {
        $request->headers->remove('X-Powered-By');
        $request->headers->remove('Server');
        $request->headers->set('X-Content-Type-Options', 'nosniff');
        $request->headers->set('X-XSS-Protection', '1; mode=block');

        $request->headers->set('Accept', 'application/json');

        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
