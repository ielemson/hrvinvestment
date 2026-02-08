<?php

// app/Providers/ViewShareServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\SiteSetting;

class ViewShareServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        View::composer('*', function ($view) {
            $settings = SiteSetting::query()->first(); // assuming single row
            $view->with('siteSettings', $settings);
        });
    }
}
