<?php

use Illuminate\Support\Facades\Http;


function wppTexto($mensagem, $numero){
    Http::post(env('API_WPP')."/rest/sendMessage/text/?id=".env('TOKEN_WPP'), [
        'receiver' => '85'.substr($numero,3),
        'message' => [
            'text' => $mensagem
        ]
    ]);
}

function wppLink($link, $descricao, $numero){
    Http::post(env('API_WPP')."/rest/sendMessage/textlink/?id=".env('TOKEN_WPP'), [
        'receiver' => '85'.substr($numero,3),
        'message' => [
            'text' => $descricao." ".$link
        ]
    ]);
}
