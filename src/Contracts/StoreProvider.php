<?php

namespace Ingenious\Store\Contracts;

interface StoreProvider {
    public function __call(string $method, array $arguments);
}
