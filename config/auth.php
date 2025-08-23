<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authentication Defaults
    |--------------------------------------------------------------------------
    */

    'defaults' => [
        'guard' => 'web',
        'passwords' => 'users',
    ],

    /*
    |--------------------------------------------------------------------------
    | Authentication Guards
    |--------------------------------------------------------------------------
    */

    'guards' => [
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],

        // Adicionado para a autenticação padrão da API (para Clientes)
        'sanctum' => [ // <-- ADICIONADO
            'driver' => 'sanctum',
            'provider' => 'users',
        ],

        // Adicionado um guard específico para os Administradores
        'admins' => [ // <-- ADICIONADO
            'driver' => 'sanctum',
            'provider' => 'admins',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */

    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            // Aponta o provider 'users' para o seu Model de Cliente
            'model' => App\Models\Cliente::class, // <-- MUDANÇA AQUI
        ],

        // Adicionado um provider específico para os Administradores
        'admins' => [ // <-- ADICIONADO
            'driver' => 'eloquent',
            'model' => App\Models\Admin::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Resetting Passwords
    |--------------------------------------------------------------------------
    */

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Password Confirmation Timeout
    |--------------------------------------------------------------------------
    */

    'password_timeout' => 10800,

];
