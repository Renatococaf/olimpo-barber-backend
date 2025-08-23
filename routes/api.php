<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\AgendamentoController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Define os endpoints para cada recurso da sua aplicação
Route::apiResource('/clientes', ClienteController::class);
Route::apiResource('/funcionarios', FuncionarioController::class);
Route::apiResource('/servicos', ServicoController::class);
Route::apiResource('/agendamentos', AgendamentoController::class);
