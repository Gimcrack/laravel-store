<?php

namespace Ingenious\Store;

use Ingenious\Store\Engines\FakeStore;
use Ingenious\Store\StoreProviderStub;
use Ingenious\Store\Contracts\StoreProvider as StoreProviderContract;

class StoreFake extends StoreProviderStub implements StoreProviderContract {

    /**
     * Get the FakeStore engine
     * @method getFakeStoreEngine
     *
     * @return   Engine
     */
    public function getFakeStoreEngine()
    {
        $this->engine = new FakeStore();

        return $this->engine;
    }

}
