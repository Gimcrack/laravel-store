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
     * @return   StdClass
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

    /**
     * Get a single product
     * @method product
     *
     * @return   StdClass
     */
    public function product(int $id) : StdClass
    {
        $atts = (array) [
            'xlink:href' => "http://fake.spreadshirt.net/api/v1/shops/1092276/products/{$id}",
            'weight' => 0,
            'lifeCycleState' => 'ACTIVATED',
            'id' => $id,
        ] + factory("Ingenious\Store\Models\FakeProduct")->make()->toArray();

        return (object) $atts;
    }

    /**
     * Get the price for a product
     * @method price
     *
     * @return   StdClass
     */
    public function price(int $id) : StdClass
    {
        return (object) [
            'vatExcluded' => 12.00
        ];


    }

}
