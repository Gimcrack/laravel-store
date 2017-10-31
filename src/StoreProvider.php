<?php

namespace Ingenious\Store;

use Ingenious\Store\StoreProviderStub;
use Ingenious\Store\Engines\SpreadShirt;
use Ingenious\Store\Contracts\StoreProvider as StoreProviderContract;

class StoreProvider extends StoreProviderStub implements StoreProviderContract {

    /**
     * Get the SpreadShirt engine
     * @method getSpreadShirtEngine
     *
     * @return   Engine
     */
    public function getSpreadShirtEngine()
    {
        $this->engine = new SpreadShirt(
            config('store.connections.spread_shirt.key'),
            config('store.connections.spread_shirt.secret'),
            config('store.connections.spread_shirt.shop_id')
        );

        return $this->engine;
    }

}
