<?php

namespace App\Console\Commands;

use App\Models\Cliente;
use Illuminate\Console\Command;
use App\Models\Cobrancas;
use Illuminate\Support\Facades\Http;

class Envia5dias extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wpp:lembrete5dias';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envia lembrete de cobrança 5 dias antes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dias = date('Y-m-d', strtotime("+5 days"));
        $fatura = Cobrancas::Join('clientes', 'clientes.id', '=', 'cobrancas.cliente_id')->whereDate('dueDate', "=" , $dias)->where('status', "PENDING")->get();
        foreach ($fatura as $fatura) {
            $cliente = Cliente::where('id', $fatura->cliente_id)->first();
            $wpp = "Olá ".$cliente->name.", tudo bem? 🤩 \nAqui é da *Fortech GPS*, pra nós é uma satisfação enorme tê-lo(a) como nosso cliente!  🚀🚀 \nEstamos te mandando essa mensagem, para lembrá-lo(a) que seu plano vence em 5 dias(".date('d/m/Y',  strtotime($fatura->dueDate))."), segue link para pagamento da cobrança";
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
