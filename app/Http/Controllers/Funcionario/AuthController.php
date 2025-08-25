<?php

namespace App\Http\Controllers\Funcionario;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Funcionario;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Lida com a tentativa de login de um funcionário via API.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 1. Encontra o funcionário pelo e-mail
        $funcionario = Funcionario::where('email', $request->email)->first();

        // 2. Verifica se o funcionário existe E se a senha está correta
        if (!$funcionario || !Hash::check($request->password, $funcionario->password)) {
            return response()->json([
                'mensagem' => 'Credenciais inválidas.'
            ], 401);
        }

        // 3. Verifica se o funcionário está ativo
        if (!$funcionario->ativo) {
            return response()->json([
                'mensagem' => 'Este usuário está inativo.'
            ], 403); // 403 Forbidden
        }

        // 4. Se tudo estiver certo, cria e retorna o token
        $token = $funcionario->createToken('funcionario-token', ['role:funcionario'])->plainTextToken;

        return response()->json([
            'mensagem' => 'Login de funcionário bem-sucedido!',
            'funcionario' => $funcionario,
            'token' => $token,
        ]);
    }

    /**
     * Lida com o logout de um funcionário via API.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'mensagem' => 'Logout bem-sucedido!'
        ]);
    }
}
