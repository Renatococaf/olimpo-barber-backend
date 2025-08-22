<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Esta única linha cria as rotas GET, POST, PUT, DELETE para clientes.
Route::apiResource('/clientes', ClienteController::class);
