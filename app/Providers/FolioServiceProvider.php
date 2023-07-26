<?php

namespace App\Providers;

use App\Folio;
use App\FolioManager;
use Illuminate\Support\ServiceProvider;

class FolioServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->singleton(FolioManager::class);

        Folio::route(resource_path('views/pages'), middleware: [
            '*' => [
                //
            ],
        ]);
    }
}
