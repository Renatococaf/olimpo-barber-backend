<?php

namespace App\Http\Controllers\Funcionario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // Usaremos a biblioteca Carbon para trabalhar com datas

class DashboardController extends Controller
{
    /**
     * Coleta e retorna todos os dados necessários para o dashboard do funcionário.
     */
    public function getDashboardData()
    {
        $funcionarioLogado = Auth::user();

        // --- Coletando os Próximos Agendamentos ---
        $proximosAgendamentos = $funcionarioLogado->agendamentos()
            ->where('data_hora_inicio', '>=', now()) // Apenas agendamentos futuros
            ->with(['cliente', 'servico'])
            ->orderBy('data_hora_inicio', 'asc')
            ->limit(5) // Pega apenas os próximos 5 para não sobrecarregar a tela
            ->get();

        // --- Coletando o Rendimento ---
        $hoje = Carbon::today();
        $inicioDoMes = Carbon::now()->startOfMonth();

        // Rendimento de Hoje
        $rendimentoHoje = $funcionarioLogado->agendamentos()
            ->where('status', 'CONCLUIDO')
            ->whereDate('data_hora_fim', $hoje)
            ->with('servico')
            ->get()
            ->sum(function ($agendamento) {
                return $agendamento->servico->preco;
            });

        // Rendimento do Mês
        $rendimentoMes = $funcionarioLogado->agendamentos()
            ->where('status', 'CONCLUIDO')
            ->whereBetween('data_hora_fim', [$inicioDoMes, now()])
            ->with('servico')
            ->get()
            ->sum(function ($agendamento) {
                return $agendamento->servico->preco;
            });

        // --- Montando o Pacote de Dados Final ---
        $dashboardData = [
            'proximos_agendamentos' => $proximosAgendamentos,
            'rendimento' => [
                'hoje' => number_format($rendimentoHoje, 2, ',', '.'),
                'este_mes' => number_format($rendimentoMes, 2, ',', '.'),
            ],
            // Futuramente, podemos adicionar aqui o calendário, etc.
        ];

        return response()->json([
            'sucesso' => true,
            'dados' => $dashboardData
        ]);
    }
}
