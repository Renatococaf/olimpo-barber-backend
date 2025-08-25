<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use App\Models\Servico;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HorarioDisponivelController extends Controller
{
    public function consultar(Request $request)
    {
        $validated = $request->validate([
            'funcionario_id' => 'required|exists:funcionarios,id',
            'servico_id' => 'required|exists:servicos,id',
            'data' => 'required|date_format:Y-m-d',
        ]);

        $funcionario = Funcionario::find($validated['funcionario_id']);
        $servico = Servico::find($validated['servico_id']);
        $data = Carbon::parse($validated['data']);
        $diaDaSemana = $data->dayOfWeek;

        // 1. Busca a disponibilidade geral do funcionário para aquele dia da semana
        $disponibilidade = $funcionario->disponibilidades()->where('dia_da_semana', $diaDaSemana)->first();

        if (!$disponibilidade) {
            return response()->json(['horarios' => []]); // Funcionário não trabalha neste dia
        }

        // 2. Busca os agendamentos já existentes para aquele dia
        $agendamentosDoDia = $funcionario->agendamentos()->whereDate('data_hora_inicio', $data->toDateString())->get();

        // 3. Calcula os horários livres
        $horaInicioTrabalho = Carbon::parse($disponibilidade->hora_inicio);
        $horaFimTrabalho = Carbon::parse($disponibilidade->hora_fim);
        $duracaoServico = $servico->duracao_minutos;

        $horariosLivres = [];

        // Cria um "cursor" que começa no início do expediente
        $horarioAtual = $horaInicioTrabalho->copy();

        while ($horarioAtual->copy()->addMinutes($duracaoServico)->lte($horaFimTrabalho)) {
            $horarioFimSlot = $horarioAtual->copy()->addMinutes($duracaoServico);
            $slotDisponivel = true;

            // Verifica se o slot atual colide com algum agendamento existente
            foreach ($agendamentosDoDia as $agendamento) {
                $agendamentoInicio = Carbon::parse($agendamento->data_hora_inicio);
                $agendamentoFim = Carbon::parse($agendamento->data_hora_fim);

                if ($horarioAtual->lt($agendamentoFim) && $horarioFimSlot->gt($agendamentoInicio)) {
                    $slotDisponivel = false;
                    break;
                }
            }

            if ($slotDisponivel) {
                $horariosLivres[] = $horarioAtual->format('H:i');
            }

            // Avança o "cursor" de 15 em 15 minutos (ou outro intervalo que preferir)
            $horarioAtual->addMinutes(15);
        }

        return response()->json(['horarios' => $horariosLivres]);
    }
}
