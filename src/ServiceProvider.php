<?php

namespace ZerosDev\ZenzivaLaravel;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            dirname(__DIR__).'/config.php' => config_path('zenziva.php'),
        ]);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        App::bind('Zenziva', function() {
            return new Zenziva;
        });
    }
}