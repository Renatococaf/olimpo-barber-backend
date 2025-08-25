<?php

namespace App\Http\Controllers;

use App\Models\Agendamento;
use App\Models\Cliente;
use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use App\Rules\HorarioDisponivelRule;
use Carbon\Carbon;

class AgendamentoController extends Controller
{
    /**
     * Lista todos os agendamentos.
     */
    public function index()
    {
        $agendamentos = Agendamento::with(['cliente', 'funcionario', 'servico'])->get();
        return response()->json(['sucesso' => true, 'dados'   => $agendamentos]);
    }

    /**
     * Cria um novo agendamento, validando a disponibilidade e aceitando convidados.
     */
    public function store(Request $request)
    {
        // --- NOVA VALIDAÇÃO ---
        $validatedData = $request->validate([
            // Dados do Agendamento
            'funcionario_id'    => 'required|exists:funcionarios,id',
            'servico_id'        => 'required|exists:servicos,id',
            'data_hora_inicio'  => [
                'required',
                'date',
                // Nossa regra personalizada que verifica a disponibilidade!
                new HorarioDisponivelRule($request->input('funcionario_id'), $request->input('servico_id'))
            ],
            'observacoes'       => 'nullable|string',

            // Dados do Cliente (um dos dois grupos é obrigatório)
            'cliente_id'        => 'sometimes|required|exists:clientes,id', // Para clientes logados

            // Para clientes convidados (só são obrigatórios se cliente_id não for enviado)
            'nome_convidado'    => 'required_without:cliente_id|string|max:255',
            'email_convidado'   => 'required_without:cliente_id|string|email|max:255',
            'telefone_convidado'=> 'required_without:cliente_id|string|max:20',
        ]);

        // --- NOVA LÓGICA ---

        // 1. Lida com o cliente: Se for um convidado, cria um registro para ele.
        $clienteId = $request->input('cliente_id');
        if (!$clienteId) {
            // Divide o nome em nome e sobrenome
            $nomes = explode(' ', $validatedData['nome_convidado'], 2);
            $nome = $nomes[0];
            $sobrenome = $nomes[1] ?? ''; // Usa string vazia se não houver sobrenome

            // Procura um cliente com aquele e-mail ou cria um novo
            $cliente = Cliente::firstOrCreate(
                ['email' => $validatedData['email_convidado']],
                [
                    'nome' => $nome,
                    'sobrenome' => $sobrenome,
                    'telefone' => $validatedData['telefone_convidado'],
                    'password' => Hash::make(Str::random(10)) // Cria uma senha aleatória
                ]
            );
            $clienteId = $cliente->id;
        }

        // 2. Calcula a data_hora_fim automaticamente com base na duração do serviço
        $servico = Servico::find($validatedData['servico_id']);
        $dataHoraFim = Carbon::parse($validatedData['data_hora_inicio'])->addMinutes($servico->duracao_minutos);

        // 3. Cria o agendamento no banco
        $agendamento = Agendamento::create([
            'cliente_id' => $clienteId,
            'funcionario_id' => $validatedData['funcionario_id'],
            'servico_id' => $validatedData['servico_id'],
            'data_hora_inicio' => $validatedData['data_hora_inicio'],
            'data_hora_fim' => $dataHoraFim, // Usa a data calculada
            'observacoes' => $validatedData['observacoes'] ?? null,
        ]);

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Agendamento criado com sucesso!',
            'dados'    => $agendamento
        ], 201);
    }

    /**
     * Mostra um agendamento específico.
     */
    public function show(Agendamento $agendamento)
    {
        $agendamento->load(['cliente', 'funcionario', 'servico']);
        return response()->json(['sucesso' => true, 'dados'   => $agendamento]);
    }

    /**
     * Atualiza um agendamento existente.
     */
    public function update(Request $request, Agendamento $agendamento)
    {
        $validatedData = $request->validate([
            'status' => ['sometimes', 'required', Rule::in(['AGENDADO', 'CONCLUIDO', 'CANCELADO', 'NAO_COMPARECEU'])],
             // Adicionar mais campos aqui se o admin/funcionário puder editar o agendamento
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
     */
    public function destroy(Agendamento $agendamento)
    {
        $agendamento->delete();
        return response()->json(['sucesso' => true, 'mensagem' => 'Agendamento deletado com sucesso!']);
    }
}
