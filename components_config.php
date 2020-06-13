<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Components folder statics section
    |--------------------------------------------------------------------------
    */
    'COMPONENTS_DIR' => env('COMPONENTS_DIR', 'app/Component/'),
    'COMPONENTS_FULL_DIR' => components_full_dir(),


    /*
    |--------------------------------------------------------------------------
    | Pagination section
    |--------------------------------------------------------------------------
    */
    'DEFAULT_PAGE_SIZE' => env('DEFAULT_PAGE_SIZE', 10),
    'DEFAULT_PAGE' => env('DEFAULT_PAGE', 1),


    /*
    |--------------------------------------------------------------------------
    | SMS section
    |--------------------------------------------------------------------------
    */
    'SMS_API_KEY' => env('SMS_API_KEY', '00000'),
    'SMS_SENDER' => env('SMS_SENDER', '0000'),
    'SMS_LOGIN' => env('SMS_LOGIN', true),


    /*
    |--------------------------------------------------------------------------
    | Order section
    |--------------------------------------------------------------------------
    */
    'MERCHANT_ID' => env('MERCHANT_ID', 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX'),
    'ORDER_PAY_URL' => env('ORDER_PAY_URL', 'https://sandbox.zarinpal.com/pg/transaction/pay/'),
    'ORDER_PAY_CALLBACK' => env('ORDER_PAY_CALLBACK', 'https://localhost/api/v1/order/verify'),
    'CREDIT_CALLBACK' => env('CREDIT_CALLBACK', 'https://localhost/api/v1/credit/verify'),
    'PAYMENT_REQUEST' => env('PAYMENT_REQUEST', 'https://sandbox.zarinpal.com/pg/services/WebGate/wsdl'),
    'PAYMENT_VERIFICATION' => env('PAYMENT_VERIFICATION', 'https://sandbox.zarinpal.com/pg/services/WebGate/wsdl'),


    /*
    |--------------------------------------------------------------------------
    | App section
    |--------------------------------------------------------------------------
    */
    'BASE_URL' => env('BASE_URL', 'https://localhost'),


];
