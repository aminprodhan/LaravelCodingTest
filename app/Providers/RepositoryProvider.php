<?php

namespace App\Providers;

use App\Interfaces\CrudInterface;
use App\Repository\ProductRepository;
use App\Repository\ProductVariantsRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CrudInterface::class,ProductRepository::class);
        $this->app->bind(CrudInterface::class,ProductVariantsRepository::class);
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
