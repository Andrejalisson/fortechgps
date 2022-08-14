<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AcessoController extends Controller{
    public function login(Request $request){
        if (Auth::check()) {
            $request->session()->flash('sucesso', 'Até Logo!');
            return redirect('/Dashboard');
        }
        $title = "Login";
        return view('acesso.login')->with(compact('title'));
    }

    public function forgot(){
        $title = "Esqueceu a senha?";
        return view('acesso.forgot')->with(compact('title'));
    }

    public function forgotPost(Request $request){
        $request->session()->flash('sucesso', 'Verifique seu e-mail');
        $request->validate([
            'email' => 'required|email|exists:users',
        ]);
        $token = Str::random(64);
        DB::table('password_resets')->insert(
            ['email' => $request->email, 'token' => $token, 'created_at' => Carbon::now()]
        );
        $usuario = User::where('email', '=',$request->email)->first();
        $uses = new \stdClass();
        $uses->name = $usuario->name;
        $uses->email = $request->email;
        $uses->token = $token;

        Mail::send(new \App\Mail\ForgotMail($uses));
        return redirect('/Login');


    }

    public function verifica(Request $request){
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], false)) {
            $request->session()->flash('sucesso', 'Bom trabalho!');
            return redirect('/Dashboard');
        }
    }


    public function logout(Request $request){
        session()->flush();
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $request->session()->flash('sucesso', 'Até Logo!');
        return redirect('/Login');
    }
}
