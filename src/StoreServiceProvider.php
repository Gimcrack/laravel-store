<?php

namespace Ingenious\Store;

use Ingenious\Store\StoreProvider;
use Illuminate\Support\ServiceProvider;

class StoreServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // config
        $this->publishes([
            __DIR__.'/Config/store.php' => config_path('store.php')
        ], 'config');

        // factory
        $this->publishes([
            __DIR__.'/Factories/FakeProductFactory.php' => database_path('factories/FakeProductFactory.php')
        ], 'config');

        // factory
        $this->publishes([
            __DIR__.'/Factories/FakeResourceFactory.php' => database_path('factories/FakeResourceFactory.php')
        ], 'config');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('Store', function() {
            return new StoreProvider( config('store.driver') );
        } );
    }
}
