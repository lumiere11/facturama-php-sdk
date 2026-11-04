<?php

/*
 * This file is part of Facturama PHP SDK.
 *
 * (c) Facturama <dev@facturama.com>
 *
 * This source file is subject to a MIT license that is bundled
 * with this source code in the file LICENSE.
 */

return [
    /*
    |--------------------------------------------------------------------------
    | Credentials
    |--------------------------------------------------------------------------
    | Facturama account user and password (HTTP basic auth).
    */
    'user' => env('FACTURAMA_USER'),
    'password' => env('FACTURAMA_PASSWORD'),

    /*
    |--------------------------------------------------------------------------
    | Environment
    |--------------------------------------------------------------------------
    | "sandbox" (default, test data only) or "production" (real CFDIs).
    */
    'environment' => env('FACTURAMA_ENV', 'sandbox'),

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    */
    'urls' => [
        'sandbox' => 'https://apisandbox.facturama.mx',
        'production' => 'https://api.facturama.mx',
    ],

    /*
    |--------------------------------------------------------------------------
    | HTTP options
    |--------------------------------------------------------------------------
    | Passed to Guzzle (connect_timeout / timeout, in seconds).
    */
    'http' => [
        'connect_timeout' => 10,
        'timeout' => 60,
    ],
];
