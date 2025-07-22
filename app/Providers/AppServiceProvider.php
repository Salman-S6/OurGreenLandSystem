<?php

namespace App\Providers;

use App\Interfaces\Crops\CropInterface;
use App\Services\Crops\CropService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
     
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
