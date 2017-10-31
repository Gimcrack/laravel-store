<?php

namespace Ingenious\Store\Contracts;

use StdClass;

interface StoreEngine {

    public function products() : StdClass;

}
