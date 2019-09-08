<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class passwordReset extends Mailable
{
    use Queueable, SerializesModels;
    public $_route;
    public $token;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->$token = $token;
        $this->_route = "http://localhost:4200/password/reset/".$token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.passwordReset')->with([$this->token,$this->_route]);
    }
}
