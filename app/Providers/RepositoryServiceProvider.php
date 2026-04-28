<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            \App\Repositories\Contracts\KategoriRepositoryInterface::class,
            \App\Repositories\Eloquent\KategoriRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\KomoditasRepositoryInterface::class,
            \App\Repositories\Eloquent\KomoditasRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\KecamatanRepositoryInterface::class,
            \App\Repositories\Eloquent\KecamatanRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\DesaRepositoryInterface::class,
            \App\Repositories\Eloquent\DesaRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\PasarRepositoryInterface::class,
            \App\Repositories\Eloquent\PasarRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\PedagangRepositoryInterface::class,
            \App\Repositories\Eloquent\PedagangRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\HargaDetailRepositoryInterface::class,
            \App\Repositories\Eloquent\HargaDetailRepository::class
        );
        $this->app->bind(
            \App\Repositories\Contracts\HargaRepositoryInterface::class,
            \App\Repositories\Eloquent\HargaRepository::class
        );
    }

    public function boot(): void
    {
        //
    }
}
