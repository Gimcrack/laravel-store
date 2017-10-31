<?php

namespace Ingenious\Store\Concerns;

use Ingenious\Store\StoreFake;
use Ingenious\Store\Facades\Store;
use Ingenious\Store\StoreProvider;

trait StoreProviderCanBeFaked {

    /**
     * Swap out the store provider
     * @method fake
     *
     * @return   this
     */
    public function fake()
    {
        Store::swap( new StoreFake('fake_store') );

        return app('Store');
    }

    /**
     * Swap out the fake provider for a real one
     * @method dontFake
     *
     * @return   this
     */
    public function dontFake()
    {
        Store::swap( new StoreProvider( config('store.driver') ) );
        return app('Store');
    }


}
