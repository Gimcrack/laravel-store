<?php

namespace Ingenious\Store\Engines;

use Cache;
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
     * @return   Collection
     */
    public function products() : StdClass
    {
        $response = (object) $this->getJson("products")->products;

        $response->data = collect($response->product);
        unset($response->product);

        return $response;
    }





}
