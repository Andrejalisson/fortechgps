<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use phpDocumentor\Reflection\DocBlock\Tags\Uses;

class ForgotMail extends Mailable
{
    use Queueable, SerializesModels;

    private $uses;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\stdClass $uses){
        $this->uses = $uses;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(){
        $this->subject("Recupeção de senha - Fortech GPS");
        $this->to($this->uses->email, $this->uses->name);
        return $this->view('mail.forgot',[
            'user' => $this->uses
        ]);
    }
}
