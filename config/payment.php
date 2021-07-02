<?php
return [
    'flutterwave' => [

        'local' => [
            'secret'   => env('FLUTTERWAVE_SECRET'),
            'public_key' => env('FLUTTERWAVE_PUBLIC_KEY'),
            'enc'  => env('FLUTTERWAVE_ENCRYPTION_KEY')
        ]
    ],
    'link'  => [
        'local' => '',
        'production' => ''
    ]
];
