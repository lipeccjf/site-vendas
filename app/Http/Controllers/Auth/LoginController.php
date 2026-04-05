<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Usuario; 
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Validação dos campos vindos do formulário
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Busca na tabela 'usuario' usando a coluna 'email' 
        $user = Usuario::where('email', $request->email)->first();

        // Verifica se o usuário existe e se a senha confere com a coluna 'senha' 
        if ($user && Hash::check($request->senha, $user->senha)) {
            
            // Realiza o login do objeto de usuário encontrado
            Auth::login($user);
            
            // Segurança: Regenera a sessão após o login
            $request->session()->regenerate();

            // Redireciona para a rota nomeada 'dashboard'
            return to_route('paginainicial');
        }

        // Caso falhe, retorna com a mensagem definida no seu HTML [cite: 4]
        return back()->with(['messagem' => 'não encontrado']);
    }
}