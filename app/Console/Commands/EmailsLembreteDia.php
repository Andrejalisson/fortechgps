<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cobrancas;
use App\Models\Logs;
use Illuminate\Support\Facades\Mail;

class EmailsLembreteDia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:lembreteDia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lembrete de pagamento do dia!';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){
        $cobrancas = Cobrancas::Join('clientes', 'clientes.id', '=', 'cobrancas.cliente_id')->whereDate('dueDate', "=" , date('Y-m-d'))->where('status', "PENDING")->get();
        foreach ($cobrancas as $cobrancas) {
            $existe = $cobrancas->email;
            if ($existe =! null) {
                $cobranca = new \stdClass();
                $primeiroNome = explode(" ", $cobrancas->name);
                $cobranca->name = $primeiroNome[0];
                $cobranca->email = $cobrancas->email;
                $cobranca->link = $cobrancas->invoiceUrl;
                Mail::send(new \App\Mail\LembreteDia($cobranca));
                $this->info("Mensagem enviada: ". $cobrancas->name);
                sleep(1);
            }else{
                $informacao = "Email de lembrete do dia não enviado para ".$cobrancas->name.", Falta de email no cadastro";
                $logs = new Logs;
                $logs->log = $informacao;
                $logs->save();
                $this->info($informacao);
            }
        }
    }
}
