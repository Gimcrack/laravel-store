<?php

namespace Ingenious\Store;

use Ingenious\Store\Concerns\StoreProviderCanBeFaked;

abstract class StoreProviderStub {

    use StoreProviderCanBeFaked;

    protected $engine;

    protected $driver;


    /**
     * Create a new instance
     * @method __construct
     *
     * @return   $this
     */
    public function __construct($driver)
    {
        $this->driver = $driver;

        $method = 'get' . studly_case($driver) . 'Engine';

        if ( method_exists($this, $method) )
        {
            $this->$method();
        }
    }


    /**
     * Call a method on the engine
     * @method __call
     *
     * @param  string  $method
     * @param  array  $arguments
     *
     * @return  mixed
     * @throws  \Exception
     */
    public function __call(string $method, array $arguments)
    {
        if ( ! method_exists($this->engine, $method) )
            throw new \Exception("Method {$method} has not been implemented on engine " . get_class($this->engine));

        return $this->engine->$method(...$arguments);
    }

}
