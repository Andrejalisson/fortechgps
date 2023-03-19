<?php

use Illuminate\Support\Facades\Http;


function wppTexto($mensagem, $numero){
    Http::withHeaders([
        'sessionkey' => 'producao'
    ])->post(env('API_WPP')."/sendText", [
        'session' => env('SESSION_WPP'),
        'number' => '5585'.substr($numero,3),
        'text' => $mensagem
    ]);
}

function wppLink($link, $descricao, $numero){
    Http::withHeaders([
        'sessionkey' => 'producao'
    ])->post(env('API_WPP')."/sendLink", [
        'session' => env('SESSION_WPP'),
        'number' => '5585'.substr($numero,3),
        'url' => $link,
        'text' => $descricao
    ]);
}
