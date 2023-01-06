<?php

use Illuminate\Support\Facades\Http;


function wppTexto($mensagem, $numero){
    Http::withHeaders([
        'sessionkey' => 'Aa@31036700.'
    ])->post(env('API_WPP')."/sendText", [
        'session' => env('SESSION_WPP'),
        'number' => '5585'.substr($numero,3),
        'text' => $mensagem
    ]);
}
