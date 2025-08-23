<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ServicoController extends Controller
{
    public function index()
    {
        return Servico::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'duracao_minutos' => 'required|integer|min:1',
            'categoria' => ['required', Rule::in(['BARBEARIA', 'SKINCARE'])],
            'pontos_olimpo_coin_gerados' => 'sometimes|integer|min:0',
        ]);

        $servico = Servico::create($validatedData);
        return response()->json($servico, 201);
    }

    public function show(Servico $servico)
    {
        return $servico;
    }

    public function update(Request $request, Servico $servico)
    {
        $validatedData = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'descricao' => 'nullable|string',
            'preco' => 'sometimes|required|numeric|min:0',
            'duracao_minutos' => 'sometimes|required|integer|min:1',
            'categoria' => ['sometimes', 'required', Rule::in(['BARBEARIA', 'SKINCARE'])],
            'pontos_olimpo_coin_gerados' => 'sometimes|integer|min:0',
        ]);

        $servico->update($validatedData);
        return response()->json($servico);
    }

    public function destroy(Servico $servico)
    {
        $servico->delete();
        return response()->json(['mensagem' => 'Serviço deletado com sucesso!']);
    }
}
