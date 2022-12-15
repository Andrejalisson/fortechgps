<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LembreteCincoDias extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        $this->subject("Recupeção de senha - Fortech GPS");
        $this->to($this->uses->email, $this->uses->name);
        return $this->view('mail.lembreteCincoDias',[
            'user' => $this->uses
        ]);
    }
}
