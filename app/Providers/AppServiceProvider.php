<?php

namespace App\Providers;

use App\CPM\Repositories\ICD9PhecodeRepository;
use App\CPM\Repositories\ICD9PhecodeRepositorySQL;
use App\CPM\Repositories\ICD9Repository;
use App\CPM\Repositories\ICD9RepositorySQL;
use App\CPM\Repositories\PhecodeRepository;
use App\CPM\Repositories\PhecodeRepositorySQL;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\View\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(ICD9Repository::class, ICD9RepositorySQL::class);
        $this->app->singleton(PhecodeRepository::class, PhecodeRepositorySQL::class);
        $this->app->singleton(ICD9PhecodeRepository::class, ICD9PhecodeRepositorySQL::class);
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        view()->composer('layouts.app', function (View $view) {
            $appName = 'Center for Precision Medicine';

            $navItems = [
                ['name' => 'Search', 'url' => url('/search')],
                [
                    'name' => 'Upload',
                    'dropdownItems' => [
                        ['name' => 'ICD9', 'url' => url('/uploadICD9')],
                        ['name' => 'Phecode', 'url' => url('/uploadPhecode')],
                        ['name' => 'Phecode to ICD9 Map', 'url' => url('/uploadICD9Phecode')],
                    ]
                ]
            ];

            $view
                ->with('appName', $appName)
                ->with('pageTitle', $appName)
                ->with('navItems', $navItems);
        });
    }
}
