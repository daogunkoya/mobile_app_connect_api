<?php

return [
    'default' => env('ADDRESS_VERIFICATION_DRIVER', 'ideal_postcode'),
    'ideal_postcodes' => [
        'driver' => 'ideal_postcode',
        'url' => env('IDEAL_POSTCODES_BASE_URL', 'https://api.ideal-postcodes.co.uk/v1/'),
        'api_key' => env('IDEAL_POSTCODES_API_KEY'),
        'timeout' => env('IDEAL_POSTCODES_API_TIMEOUT', '30'),
    ],
];
