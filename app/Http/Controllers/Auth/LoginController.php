<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');


    }

    public function login()
    {
        dd(request()->all());




        if ($user = User::query()
        ->where('email', '=', request('email'))
        ->first()) {

       if(Hash::check(request()->password, $user->password)) {
         auth()->login($user);
        return to_route('dashboard');
        }

       
       }

       return back()->with(['message' => 'não encontrado']);
    

        dd(request()->all());


    }
}