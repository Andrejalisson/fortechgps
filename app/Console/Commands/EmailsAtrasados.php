<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cobrancas;
use App\Models\Logs;
use Illuminate\Support\Facades\Mail;

class EmailsAtrasados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:atrasados';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Emails Atrasados';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){
        $cobrancas = Cobrancas::Join('clientes', 'clientes.id', '=', 'cobrancas.cliente_id')->where('status', "OVERDUE")->get();
        foreach ($cobrancas as $cobrancas) {
            $existe = $cobrancas->email;
            if ($existe =! null) {
                $cobranca = new \stdClass();
                $primeiroNome = explode(" ", $cobrancas->name);
                $cobranca->name = $primeiroNome[0];
                $cobranca->email = $cobrancas->email;
                $cobranca->link = $cobrancas->invoiceUrl;
                Mail::send(new \App\Mail\LembreteAtrasado($cobranca));
                $informacao = "Email de cobrança atrasada enviado para ".$cobrancas->name;
                $marcelo = "85988173101";
                $andre = "85985965372";
                wppTexto($informacao,$marcelo);
                wppTexto($informacao,$andre);
                $this->info("Mensagem enviada: ". $cobrancas->name);
                $logs = new Logs;
                $logs->log = $informacao;
                $logs->save();
                $this->info($informacao);
                sleep(1);
            }else{
                $informacao = "Email de cobrança atrasada não enviado para ".$cobrancas->name." Falta de email no cadastro";
                $logs = new Logs;
                $logs->log = $informacao;
                $logs->save();
                $this->info($informacao);
            }

        }
    }
}
