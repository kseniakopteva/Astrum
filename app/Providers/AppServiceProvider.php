<?php

namespace App\Providers;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
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

        // Merged Collection Paginator
        Collection::macro('paginate', function ($perPage, $total = null, $page = null, $pageName = 'page') {
            $page = $page ?: LengthAwarePaginator::resolveCurrentPage($pageName);

            $currentPath    = LengthAwarePaginator::resolveCurrentPath();
            if (strpos($currentPath, '/page/') !== false) {
                list($currentPath,)    = explode('/page/', $currentPath);
            }
            return new LengthAwarePaginator(
                $this->forPage($page, $perPage)->values(),
                $total ?: $this->count(),
                $perPage,
                $page,
                [
                    'path' => $currentPath, /*LengthAwarePaginator::resolveCurrentPath(),*/
                    'pageName' => $pageName,
                ]

            );
        });
    }
}
