<?php

namespace Ingenious\Store\Engines;


use StdClass;
use Zttp\Zttp;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Ingenious\Store\StoreEngineStub;
use Ingenious\Store\Contracts\StoreEngine as StoreEngineContract;

class SpreadShirt extends StoreEngineStub implements StoreEngineContract {

    protected $key;

    protected $secret;

    protected $shopId;

    public function __construct($key, $secret, $shopId)
    {
        $this->key = $key;
        $this->secret = $secret;
        $this->shopId = $shopId;

        $this->endpoint = "http://api.spreadshirt.net/api/v1/shops/{$this->shopId}";
    }

    /**
     * Get a list of products
     * @method products
     *
     * @return   StdClass
     */
    public function products() : StdClass
    {
        $response = (object) $this->getJson("products");

        if ( ! isset($response->products) )
            abort(404);

        $response = (object) $response->products;

        $response->data = collect($response->product)
            ->transform( function($product) {
                return (object) $product;
            });

        unset($response->product);

        return $response;
    }

    /**
     * Get a single product resource
     * @method product
     *
     * @return   StdClass
     */
    public function product(int $id) : StdClass
    {
        $response = (object) $this->getJson("products/{$id}");

        if ( ! isset($response->product) )
            abort(404);

        return (object) $response->product;
    }





}
