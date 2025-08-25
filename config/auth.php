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
        'funcionarios' => [
        'driver' => 'sanctum',
        'provider' => 'funcionarios',
    ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Providers
    |--------------------------------------------------------------------------
    */
'providers' => [
    'users' => [ // Provider para Clientes
        'driver' => 'eloquent',
        'model' => App\Models\Cliente::class,
    ],

    'admins' => [ // Provider para Admins
        'driver' => 'eloquent',
        'model' => App\Models\Admin::class,
    ],

    'funcionarios' => [ // Provider para Funcionários
        'driver' => 'eloquent',
        'model' => App\Models\Funcionario::class,
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
