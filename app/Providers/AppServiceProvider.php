<?php

namespace App\Providers;

use App\Http\ViewComposers\NotificationComposer;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('notrole', function ($role) {
            return !auth()->user()->hasRole($role);
        });

        View::composer('layouts.main-layout', NotificationComposer::class);
    }
}
