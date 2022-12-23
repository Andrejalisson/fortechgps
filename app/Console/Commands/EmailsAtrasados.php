<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Cobrancas;
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
            $cobranca = new \stdClass();
            $primeiroNome = explode(" ", $cobrancas->name);
            $cobranca->name = $primeiroNome[0];
            $cobranca->email = $cobrancas->email;
            $cobranca->link = $cobrancas->invoiceUrl;
            Mail::send(new \App\Mail\LembreteAtrasado($cobranca));
            $informação = "Email de cobrança atrasada enviado para ".$cobrancas->name;
            $this->info($informação);
            sleep(3);
        }
    }
}
