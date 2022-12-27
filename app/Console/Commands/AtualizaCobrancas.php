<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use CodePhix\Asaas\Asaas;
use App\Models\Cobrancas;
use App\Models\Cliente;
use Illuminate\Support\Facades\Http;

class AtualizaCobrancas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'atualiza:cobrancas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Atualiza Cobranças';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){
        $asaas = new Asaas(env('API_ASSAS'), 'producao');
        $filtro = array(
            'limit' => 100,
        );
        $cobrancas = $asaas->Cobranca()->getAll($filtro);
        // dd($cobrancas);
        foreach ($cobrancas->data as $cobranca) {
            $fatura = Cobrancas::where('externalReference', $cobranca->id)->first();
            $quantidade = Cobrancas::where('externalReference', $cobranca->id)->count();
            if ($quantidade > 0) {
                $cliente = Cliente::where('externalReference', $cobranca->customer)->first();
                if($cobranca->status =! $fatura->status) {
                    switch ($cobranca->status) {
                        case 'RECEIVED':
                            $wpp =  "Cliente ".$cliente->name." Pagou a fatura no dia ".date('d/m/Y',  strtotime($cobranca->clientPaymentDate))."\n Valor Líguido: R$ ".number_format($cobranca->netValue, 2, ',', '.');
                            break;
                        case 'CONFIRMED':
                            $wpp =  "Cliente ".$cliente->name." Pagou a fatura no dia ".date('d/m/Y',  strtotime($cobranca->clientPaymentDate))."\n Valor Líguido: R$ ".number_format($cobranca->netValue, 2, ',', '.');
                            break;
                        case 'OVERDUE':
                            $wpp =  "Cliente ".$cliente->name." Não pagou a fatura do dia ".date('d/m/Y',  strtotime($cobranca->dueDate))."\n Valor Líguido: R$ ".number_format($cobranca->netValue, 2, ',', '.');
                            break;

                        default:
                            $wpp = "A cobrança veio diferente ".$cobranca->status." do cliente ".$cliente->name;
                            break;
                    }
                    Http::withHeaders([
                        'sessionkey' => '31036700'
                    ])->post(env('API_WPP')."/sendText", [
                        'session' => env('SESSION_WPP'),
                        'number' => '558585965372',
                        'text' => $wpp
                    ]);
                    $this->info("Mensagem enviada: ". $wpp);
                }else{
                    $this->info("Sem alteração do ".$cliente->name);
                }
            }
        }
    }
}
