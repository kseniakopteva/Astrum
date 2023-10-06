<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::unguard();

        Blade::if('admin', function () {
            return auth()->user() && auth()->user()->role == 'admin';
        });

        Blade::if('mod', function () {
            return auth()->user() && auth()->user()->role == 'mod';
        });

        Blade::if('creator', function () {
            return auth()->user() && auth()->user()->role == 'creator';
        });
    }
}
