<?php

namespace Ingenious\Store\Engines;

use StdClass;
use Ingenious\Store\StoreEngineStub;
use Ingenious\Store\Contracts\StoreEngine as StoreEngineContract;

class FakeStore extends StoreEngineStub implements StoreEngineContract {

    /**
     * Get a list of products
     * @method products
     *
     * @return   Collection
     */
    public function products() : StdClass
    {
        return (object) [
            'xlink:href' => 'http://fake.spreadshirt.net/api/v1/shops/1092276/products',
            'offset' => 0,
            'limit' => 50,
            'count' => 5,
            'sortField' => 'default',
            'sortOrder' => 'default',
            'data' => factory("Ingenious\Store\Models\FakeProduct",5)->make()
        ];
    }

}
