<?php
namespace Ingenious\Store\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Ingenious\Store\StoreProvider
 */
class Store extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Store';
    }
}
