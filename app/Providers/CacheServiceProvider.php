<?php

namespace App\Providers;

use App\Models\Denomination;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;

class CacheServiceProvider extends ServiceProvider
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
        Cache::remember('product_list', now()->addHour(), function () {
            return Product::active()->get();
        });
    
        Cache::remember('denomination_list', now()->addHours(24), function () {
            return Denomination::active()->get();
        });

    }
}
