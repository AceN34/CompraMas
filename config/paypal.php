<?php

return [

    /*
    |--------------------------------------------------------------------------
    | PayPal Configuration
    |--------------------------------------------------------------------------
    |
    | Las siguientes credenciales son necesarias para autenticarte con la API
    | REST de PayPal usando el SDK oficial (paypal-checkout-sdk).
    |
    | PAYPAL_MODE puede ser 'sandbox' o 'live'
    |
    */

    'client_id' => env('PAYPAL_CLIENT_ID', ''),
    'client_secret' => env('PAYPAL_SECRET', ''),
    'mode' => env('PAYPAL_MODE', 'sandbox'), // 'sandbox' o 'live'
];
