<?php

namespace App\Mail;

use App\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Alertas extends Mailable
{
    use Queueable, SerializesModels;

    private $eventos = [];

    public function __construct($eventos)
    {
        $this->eventos = $eventos;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(env('MAIL_FROM', 'teste@teste.com'))
            ->bcc(env('MAIL_FROM', 'teste@teste.com'))
            ->view('mail.alertas')
            ->with(['eventos' => $this->eventos]);

    }

}
