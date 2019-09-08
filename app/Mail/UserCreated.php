<?php

namespace App\Mail;

use App\Student;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserCreated extends Mailable
{
    use Queueable, SerializesModels;
    public $user;
    public $_route;

    /**
     * Create a new message instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->_route = "http://localhost:4200/verify/".$this->user->verification_token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.emailValidation')
            ->with(['route'=>$this->_route])
            ->subject('Activación de Cuenta');
    }
}
