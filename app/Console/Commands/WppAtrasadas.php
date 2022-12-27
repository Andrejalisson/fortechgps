<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use Illuminate\Console\Command;
use App\Models\Cobrancas;
use Illuminate\Support\Facades\Http;

class WppAtrasadas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wpp:atrasadas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia cobranças atrasadas via Whatsapp';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){
        $fatura = Cobrancas::where('status', 'OVERDUE')->get();
        foreach ($fatura as $fatura) {
            $cliente = Cliente::where('id', $fatura->cliente_id)->first();
            $wpp = "Olá ".$cliente->name.", tudo bem? 🤩 \n Aqui é da Fortech GPS, pra nós é uma satisfação enorme tê-lo(a) como nosso cliente!  🚀🚀 \n Estou te mandando essa mensagem, para lembrá-lo(a) que seu plano venceu no dia ".date('d/m/Y',  strtotime($fatura->dueDate)).", segue link para pagamento da cobrança";
            Http::withHeaders([
                'sessionkey' => '31036700'
            ])->post(env('API_WPP')."/sendText", [
                'session' => env('SESSION_WPP'),
                'number' => '5585'.substr($cliente->mobilePhone,3),
                'text' => $wpp
            ]);
            Http::withHeaders([
                'sessionkey' => '31036700'
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
