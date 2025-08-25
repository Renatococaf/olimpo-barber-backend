<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FuncionarioController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\AgendamentoController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Funcionario\AuthController as FuncionarioAuthController;
use App\Http\Controllers\Funcionario\DashboardController;
use App\Http\Controllers\Funcionario\DisponibilidadeController;
use App\Http\Controllers\HorarioDisponivelController;

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
Route::get('/horarios-disponiveis', [HorarioDisponivelController::class, 'consultar']);

// Grupo de rotas para o Admin para manter organizado
Route::prefix('admin')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);

    // A rota de logout só será acessível se o admin estiver logado com um token válido
    // O middleware 'auth:admins' usa o guard que criamos
    Route::middleware('auth:admins')->post('/logout', [AuthController::class, 'logout']);
});

// Grupo de rotas para o Funcionario para manter organizado
Route::prefix('funcionario')->group(function () {
    Route::post('/login', [FuncionarioAuthController::class, 'login']);

    // A rota de logout só será acessível se o funcionário estiver logado com um token válido
    Route::middleware('auth:funcionarios')->post('/logout', [FuncionarioAuthController::class, 'logout']);
});

Route::middleware('auth:funcionarios')->prefix('funcionario')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'getDashboardData']);

    Route::apiResource('/disponibilidade', DisponibilidadeController::class);
});
