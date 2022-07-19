<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Usuarios;

class AcessoController extends Controller{
    public function login(){
        $title = "Login";
        return view('acesso.login')->with(compact('title'));
    }

    public function forgot(){
        $title = "Esqueceu a senha?";
        return view('acesso.forgot')->with(compact('title'));
    }

    public function verifica(Request $request){
        $usuario = Usuarios::where('email', $request->login)->orWhere('username', $request->login)->first();
        if($usuario != null){
            if($usuario->status == 1){
                if(password_verify($request->senha, $usuario->senha )){
                    $request->session()->flash('sucesso', 'Bom trabalho!');
                    $request->session()->put('id', $usuario->id);
                    $request->session()->put('nome', $usuario->nome);
                    $request->session()->put('login', $usuario->username);
                    $request->session()->put('logado', true);
                    return redirect('/Clientes');
                }else{
                    switch ($usuario->tentativas) {
                        case 0:
                            $request->session()->flash('atencao', 'Senha incorreta! Você tem mais 2 chances.');
                            Usuarios::where('id', $usuario->id_user)
                                ->update(['tentativas' => 1]);
                            break;
                        case 1:
                            $request->session()->flash('atencao', 'Senha incorreta! Você tem mais 1 chances.');
                            Usuarios::where('id', $usuario->id_user)
                                ->update(['tentativas' => 2]);
                            break;
//                        case 2:
//                            $request->session()->flash('erro', 'Senha incorreta! Usuário Bloqueado.');
//                            $token = uniqid("BL", true);
//                            DB::table('recuperacao')
//                                ->insert([
//                                    'token_rec' => $token,
//                                    'user_id_rec' => $usuario->id_user,
//                                    'criacao_rec' => date('Y-m-d H:i:s')
//                                ]);
//                            DB::table('usuarios')
//                                ->where('id_user', $usuario->id_user)
//                                ->update([
//                                    'tentativas_user' => 3,
//                                    'status_user' => 0
//                                ]);
//                            Mail::send(new \App\Mail\desbloqueioUsuario($usuario, $token));
//                            break;
                    }
                    return redirect()->back();
                }
            }else{
                $request->session()->flash('erro', 'Usuário Bloqueado!');
                return redirect()->back();
            }
        }else{
            $request->session()->flash('atencao', 'Usuário não encontrado.');
            return redirect()->back();
        }
    }


    public function logout(Request $request){
        session()->flush();
        $request->session()->flash('sucesso', 'Até Logo!');
        return redirect('/Login');
    }
}
