<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LembreteDia extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\stdClass $cobranca){
        $this->cobranca = $cobranca;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        $this->subject("Fatura Disponível - Fortech GPS");
        $this->to("andrejalisson@gmail.com", $this->cobranca->name);
        return $this->view('mail.lembreteDia',[
            'cobranca' => $this->cobranca
        ]);
    }
}