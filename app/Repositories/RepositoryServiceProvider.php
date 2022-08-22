<?php
namespace App\Repositories;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'App\Repositories\ChannexRepositoryInterface',
            'App\Repositories\ChannexRepository'
        );
        $this->app->bind(
            'App\Repositories\BookingRepositoryInterface',
            'App\Repositories\BookingRepository'
        );
        $this->app->bind(
            'App\Repositories\PaymentRepositoryInterface',
            'App\Repositories\PaymentRepository'
        );
    }
}
