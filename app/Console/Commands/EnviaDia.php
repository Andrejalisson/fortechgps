<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use Illuminate\Console\Command;
use App\Models\Cobrancas;
use Illuminate\Support\Facades\Http;

class EnviaDia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wpp:lembreteDia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio do lembrete das cobranças do dia';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){
        $fatura = Cobrancas::Join('clientes', 'clientes.id', '=', 'cobrancas.cliente_id')->whereDate('dueDate', "=" , date('Y-m-d'))->where('status', "PENDING")->get();
        foreach ($fatura as $fatura) {
            $cliente = Cliente::where('id', $fatura->cliente_id)->first();
            $wpp = "Olá ".$cliente->name.", tudo bem? 🤩 \nAqui é da *Fortech GPS*, pra nós é uma satisfação enorme tê-lo(a) como nosso cliente!  🚀🚀 \nEstamos te mandando essa mensagem, para lembrá-lo(a) que seu plano vence *hoje*(".date('d/m/Y',  strtotime($fatura->dueDate))."), segue link para pagamento da cobrança";
            Http::withHeaders([
                'sessionkey' => 'Aa@31036700.'
            ])->post(env('API_WPP')."/sendText", [
                'session' => env('SESSION_WPP'),
                'number' => '5585'.substr($cliente->mobilePhone,3),
                'text' => $wpp
            ]);
            Http::withHeaders([
                'sessionkey' => 'Aa@31036700.'
            ])->post(env('API_WPP')."/sendLink", [
                'session' => env('SESSION_WPP'),
                'number' => '5585'.substr($cliente->mobilePhone,3),
                'url' => $fatura->invoiceUrl,
                'text' => ""
            ]);
            $this->info("Mensagem enviada: ". $cliente->name);
        }
    }
}
