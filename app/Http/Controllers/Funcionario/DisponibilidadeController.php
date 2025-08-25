<?php

namespace App\Http\Controllers\Funcionario;

use App\Http\Controllers\Controller;
use App\Models\Disponibilidade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DisponibilidadeController extends Controller
{
    /**
     * Lista os horários de disponibilidade do funcionário autenticado.
     * Rota: GET /api/funcionario/disponibilidade
     */
    public function index()
    {
        $disponibilidades = Auth::user()->disponibilidades()->orderBy('dia_da_semana')->get();

        return response()->json($disponibilidades);
    }

    /**
     * Cria um novo registro de disponibilidade para o funcionário autenticado.
     * Rota: POST /api/funcionario/disponibilidade
     */
    public function store(Request $request)
    {
        $funcionarioId = Auth::id();

        $validatedData = $request->validate([
            'dia_da_semana' => [
                'required',
                'integer',
                'between:0,6', // Garante que o dia seja entre 0 (Domingo) e 6 (Sábado)
                Rule::unique('disponibilidades')->where(function ($query) use ($funcionarioId) {
                    return $query->where('funcionario_id', $funcionarioId);
                }),
            ],
            'hora_inicio' => 'required|date_format:H:i', // Formato "14:30"
            'hora_fim' => 'required|date_format:H:i|after:hora_inicio',
        ]);

        // Adiciona o ID do funcionário logado aos dados validados
        $validatedData['funcionario_id'] = $funcionarioId;

        $disponibilidade = Disponibilidade::create($validatedData);

        return response()->json($disponibilidade, 201);
    }

    /**
     * Mostra um registro de disponibilidade específico.
     * Rota: GET /api/funcionario/disponibilidade/{disponibilidade}
     */
    public function show(Disponibilidade $disponibilidade)
    {
        // VERIFICAÇÃO DE SEGURANÇA: O registro pertence ao usuário logado?
        if ($disponibilidade->funcionario_id !== Auth::id()) {
            return response()->json(['mensagem' => 'Acesso não autorizado.'], 403); // 403 Forbidden
        }

        return response()->json($disponibilidade);
    }

    /**
     * Atualiza um registro de disponibilidade.
     * Rota: PUT /api/funcionario/disponibilidade/{disponibilidade}
     */
    public function update(Request $request, Disponibilidade $disponibilidade)
    {
        // VERIFICAÇÃO DE SEGURANÇA
        if ($disponibilidade->funcionario_id !== Auth::id()) {
            return response()->json(['mensagem' => 'Acesso não autorizado.'], 403);
        }

        $validatedData = $request->validate([
            'hora_inicio' => 'sometimes|required|date_format:H:i',
            'hora_fim' => 'sometimes|required|date_format:H:i|after:hora_inicio',
        ]);

        $disponibilidade->update($validatedData);

        return response()->json($disponibilidade);
    }

    /**
     * Deleta um registro de disponibilidade.
     * Rota: DELETE /api/funcionario/disponibilidade/{disponibilidade}
     */
    public function destroy(Disponibilidade $disponibilidade)
    {
        // VERIFICAÇÃO DE SEGURANÇA
        if ($disponibilidade->funcionario_id !== Auth::id()) {
            return response()->json(['mensagem' => 'Acesso não autorizado.'], 403);
        }

        $disponibilidade->delete();

        return response()->json(['mensagem' => 'Disponibilidade removida com sucesso!']);
    }
}
