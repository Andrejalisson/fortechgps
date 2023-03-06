<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cobrancas;
use App\Models\Logs;
use Illuminate\Support\Facades\Mail;

class EmailsLembrete5Dia extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emails:lembrete5Dia';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envio de emails para daqui a 5 dias';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(){
        $dias = date('Y-m-d', strtotime("+5 days"));
        $cobrancas = Cobrancas::Join('clientes', 'clientes.id', '=', 'cobrancas.cliente_id')->whereDate('dueDate', "=" , $dias)->where('status', "PENDING")->get();
        foreach ($cobrancas as $cobrancas) {
            $existe = $cobrancas->email;
            if ($existe =! null) {
                $cobranca = new \stdClass();
                $primeiroNome = explode(" ", $cobrancas->name);
                $cobranca->name = $primeiroNome[0];
                $cobranca->email = $cobrancas->email;
                $cobranca->link = $cobrancas->invoiceUrl;
                Mail::send(new \App\Mail\LembreteCincoDias($cobranca));
                $this->info("Mensagem enviada: ". $cobrancas->name);
                $logs = new Logs;
                $logs->log = $informacao;
                $logs->save();
                $this->info($informacao);
                sleep(1);
            }else{
                $informacao = "Email de Lembrete 5 dias não enviado para ".$cobrancas->name." Falta de email no cadastro";
                $logs = new Logs;
                $logs->log = $informacao;
                $logs->save();
                $this->info($informacao);
            }

        }
    }
}
