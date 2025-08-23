<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash; // Importe a classe Hash

class AuthController extends Controller
{
    /**
     * Lida com a tentativa de login de um administrador.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 1. Encontra o admin pelo e-mail
        $admin = Admin::where('email', $request->email)->first();

        // 2. Verifica se o admin existe E se a senha está correta
        if (!$admin || !Hash::check($request->password, $admin->password)) {
            return response()->json([
                'mensagem' => 'Credenciais inválidas.'
            ], 401); // 401 Unauthorized
        }

        // 3. Se as credenciais estiverem corretas, cria e retorna o token
        $token = $admin->createToken('admin-token', ['role:admin'])->plainTextToken;

        return response()->json([
            'mensagem' => 'Login de administrador bem-sucedido!',
            'admin' => $admin,
            'token' => $token,
        ]);
    }

    /**
     * Lida com o logout de um administrador.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'mensagem' => 'Logout bem-sucedido!'
        ]);
    }
}
