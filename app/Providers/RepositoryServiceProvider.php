<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repository\UserInterfaceRepo;
use App\Repository\UserRepo;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(UserInterfaceRepo::class, UserRepo::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
