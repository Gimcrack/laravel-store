<?php

return [

    'driver' => env('STORE_DRIVER'),

    'connections' => [

        'spread_shirt' => [
            'key' => env('SPREADSHIRT_KEY'),
            'secret' => env('SPREADSHIRT_SECRET'),
            'shop_id' => env('SPREADSHIRT_SHOP_ID'),
        ],
    ],
];
