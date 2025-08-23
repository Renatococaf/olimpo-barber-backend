<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Importante para a validação do status

class AgendamentoController extends Controller
{
    /**
     * Lista todos os agendamentos.
     * Rota: GET /api/agendamentos
     */
    public function index()
    {
        // Usamos with() para carregar os dados relacionados de forma otimizada
        $agendamentos = Agendamento::with(['cliente', 'funcionario', 'servico'])->get();

        return response()->json([
            'sucesso' => true,
            'dados'   => $agendamentos
        ]);
    }

    /**
     * Cria um novo agendamento.
     * Rota: POST /api/agendamentos
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cliente_id'        => 'required|exists:clientes,id',
            'funcionario_id'    => 'required|exists:funcionarios,id',
            'servico_id'        => 'required|exists:servicos,id',
            'data_hora_inicio'  => 'required|date',
            'data_hora_fim'     => 'required|date|after:data_hora_inicio',
            'observacoes'       => 'nullable|string',
            'status'            => ['nullable', Rule::in(['AGENDADO', 'CONCLUIDO', 'CANCELADO', 'NAO_COMPARECEU'])],
        ]);

        $agendamento = Agendamento::create($validatedData);

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Agendamento criado com sucesso!',
            'dados'    => $agendamento
        ], 201);
    }

    /**
     * Mostra um agendamento específico.
     * Rota: GET /api/agendamentos/{agendamento}
     */
    public function show(Agendamento $agendamento)
    {
        // O Laravel já encontra o agendamento pelo ID na URL (Route Model Binding)
        // Apenas carregamos os dados relacionados para a resposta ser completa.
        $agendamento->load(['cliente', 'funcionario', 'servico']);

        return response()->json([
            'sucesso' => true,
            'dados'   => $agendamento
        ]);
    }

    /**
     * Atualiza um agendamento existente.
     * Rota: PUT /api/agendamentos/{agendamento}
     */
    public function update(Request $request, Agendamento $agendamento)
    {
        $validatedData = $request->validate([
            'cliente_id'        => 'sometimes|required|exists:clientes,id',
            'funcionario_id'    => 'sometimes|required|exists:funcionarios,id',
            'servico_id'        => 'sometimes|required|exists:servicos,id',
            'data_hora_inicio'  => 'sometimes|required|date',
            'data_hora_fim'     => 'sometimes|required|date|after:data_hora_inicio',
            'observacoes'       => 'nullable|string',
            'status'            => ['sometimes', 'required', Rule::in(['AGENDADO', 'CONCLUIDO', 'CANCELADO', 'NAO_COMPARECEU'])],
        ]);

        $agendamento->update($validatedData);

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Agendamento atualizado com sucesso!',
            'dados'    => $agendamento
        ]);
    }

    /**
     * Deleta um agendamento.
     * Rota: DELETE /api/agendamentos/{agendamento}
     */
    public function destroy(Agendamento $agendamento)
    {
        $agendamento->delete();

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Agendamento deletado com sucesso!'
        ], 200);
    }
}
