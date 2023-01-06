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
            wppTexto($wpp, $cliente->mobilePhone);
            $texto = "";
            wppLink($fatura->invoiceUrl,$texto, $cliente->mobilePhone);
            $mensagem = "A cobrança do cliente ".$cliente->name." foi enviado via Whatsapp";
            $marcelo = "85988173101";
            $andre = "85985965372";
            wppTexto($mensagem,$marcelo);
            wppTexto($mensagem,$andre);
            $this->info("Mensagem enviada: ". $cliente->name);
        }
    }
}
