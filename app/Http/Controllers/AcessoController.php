<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

class AcessoController extends Controller{
    public function login(Request $request){
        if (Auth::check()) {
            $request->session()->flash('sucesso', 'Bem vindo novamente!');
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
        DB::delete('delete from password_resets where email = ?',[$request->email]);
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

    public function verificaToken($token){
        $users = DB::select('select * from password_resets where token = ?',[$token]);
        $horas = Carbon::now()->diffInHours($users[0]->created_at);
        if($horas > 24){
            $title = "Token Expirado?";
            return view('acesso.expirado')->with(compact('title'));
        }else{
            $title = "Nova senha";
            return view('acesso.novasenha')->with(compact('title','token'));
        }
    }

    public function novaSenha(Request $request, $token){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);
        $users = DB::select('select * from password_resets where token = ?',[$token]);
        User::where('email', '=',$users[0]->email)->update(['password' => Hash::make(Input::get('password'))]);



    }

    public function verifica(Request $request){
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], false)) {
            $request->session()->flash('sucesso', 'Bom trabalho!');

            $response = Http::accept('application/json')->post('https://public-api.softruck.com/api/v1/auth/login', [
                'username' => 'andrejalisson',
                'password' => 'Aa@31036700.',
            ]);
            $token = json_decode($response->body());
            foreach ($token as $dados) {
                $request->session()->put('tokenSoftruck', $dados->token);
            }
            return redirect('/Empresas');
        }
        $request->session()->flash('atencao', 'Email ou senha incorretos.');
        return redirect()->back();
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
