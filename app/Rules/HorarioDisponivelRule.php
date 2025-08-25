<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\Funcionario;
use App\Models\Servico;
use Carbon\Carbon;

class HorarioDisponivelRule implements Rule
{
    protected $funcionarioId;
    protected $servicoId;

    public function __construct($funcionarioId, $servicoId)
    {
        $this->funcionarioId = $funcionarioId;
        $this->servicoId = $servicoId;
    }

    public function passes($attribute, $value)
    {
        // Esta lógica é uma simplificação da que está no HorarioDisponivelController
        // e pode ser expandida para ser idêntica.
        $funcionario = Funcionario::find($this->funcionarioId);
        $dataHoraInicio = Carbon::parse($value);
        $diaDaSemana = $dataHoraInicio->dayOfWeek;

        $disponibilidade = $funcionario->disponibilidades()->where('dia_da_semana', $diaDaSemana)->first();
        if (!$disponibilidade ||
            $dataHoraInicio->format('H:i:s') < $disponibilidade->hora_inicio ||
            $dataHoraInicio->format('H:i:s') > $disponibilidade->hora_fim) {
            return false; // Fora do horário de trabalho
        }

        // Aqui viria a lógica para checar colisão com outros agendamentos (para 100% de segurança)

        return true;
    }

    public function message()
    {
        return 'O horário selecionado não está disponível.';
    }
}
