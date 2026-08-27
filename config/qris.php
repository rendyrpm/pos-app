<?php

return [

    /*
    |--------------------------------------------------------------------------
    | QRIS Merchant Configuration
    |--------------------------------------------------------------------------
    |
    | Konfigurasi merchant QRIS untuk pembayaran statis.
    | QR code yang ditampilkan berisi informasi merchant ini.
    |
    */

    'merchant_name' => env('QRIS_MERCHANT_NAME', 'POS App'),

    'merchant_id' => env('QRIS_MERCHANT_ID', '00000000000000'),

];
