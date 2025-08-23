<?php

namespace App\Http\Controllers;

use App\Models\Funcionario;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FuncionarioController extends Controller
{
    public function index()
    {
        return Funcionario::all();
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome_completo' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:funcionarios',
            'password' => 'required|string|min:8',
            'tipo_funcionario' => ['required', Rule::in(['BARBEIRO', 'SKINCARE'])],
            'ativo' => 'sometimes|boolean',
        ]);

        $funcionario = Funcionario::create($validatedData);

        return response()->json($funcionario, 201);
    }

    public function show(Funcionario $funcionario)
    {
        return $funcionario;
    }

    public function update(Request $request, Funcionario $funcionario)
    {
        $validatedData = $request->validate([
            'nome_completo' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('funcionarios')->ignore($funcionario->id)],
            'tipo_funcionario' => ['sometimes', 'required', Rule::in(['BARBEIRO', 'SKINCARE'])],
            'ativo' => 'sometimes|boolean',
        ]);

        $funcionario->update($validatedData);

        return response()->json($funcionario);
    }

    public function destroy(Funcionario $funcionario)
    {
        $funcionario->delete();
        return response()->json(['mensagem' => 'Funcionário deletado com sucesso!']);
    }
}
