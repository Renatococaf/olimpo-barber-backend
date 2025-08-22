<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Importante para a validação do update

class ClienteController extends Controller
{
    /**
     * Lista todos os clientes.
     * Rota: GET /api/clientes
     */
    public function index()
    {
        $clientes = Cliente::all();
        return response()->json([
            'sucesso' => true,
            'dados'   => $clientes
        ]);
    }

    /**
     * Cria um novo cliente.
     * Rota: POST /api/clientes
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome'      => 'required|string|max:100',
            'sobrenome' => 'required|string|max:100',
            'email'     => 'required|string|email|max:255|unique:clientes',
            'password'  => 'required|string|min:8',
            'telefone'  => 'nullable|string|max:20',
        ]);

        $cliente = Cliente::create($validatedData);

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Cliente criado com sucesso!',
            'dados'    => $cliente
        ], 201);
    }

    /**
     * Mostra um cliente específico.
     * Rota: GET /api/clientes/{cliente}
     */
    public function show(Cliente $cliente)
    {
        // O Laravel automaticamente encontra o cliente pelo ID na URL (Route Model Binding)
        return response()->json([
            'sucesso' => true,
            'dados'   => $cliente
        ]);
    }

    /**
     * Atualiza um cliente existente.
     * Rota: PUT /api/clientes/{cliente}
     */
    public function update(Request $request, Cliente $cliente)
    {
        $validatedData = $request->validate([
            'nome'      => 'sometimes|required|string|max:100',
            'sobrenome' => 'sometimes|required|string|max:100',
            'email'     => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('clientes')->ignore($cliente->id), // Permite que o cliente mantenha seu próprio e-mail
            ],
            'telefone'  => 'nullable|string|max:20',
        ]);

        $cliente->update($validatedData);

        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Cliente atualizado com sucesso!',
            'dados'    => $cliente
        ]);
    }

    /**
     * Deleta um cliente.
     * Rota: DELETE /api/clientes/{cliente}
     */
    public function destroy(Cliente $cliente)
    {
        $cliente->delete();

        // A resposta 204 No Content é o padrão para delete bem-sucedido
        return response()->json([
            'sucesso' => true,
            'mensagem' => 'Cliente deletado com sucesso!'
        ], 200); // Pode ser 200 OK com mensagem ou 204 No Content sem corpo
    }
}
