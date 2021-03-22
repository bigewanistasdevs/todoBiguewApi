<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class newLaravelTips extends Mailable
{
    use Queueable, SerializesModels;

    private $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(\stdClass $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->subject($this->user->assunto);
        // $this->subject("conteudo heheh");
		$this->to($this->user->email_prof, $this->user->nome_prof);
		// $this->to($this->user->email_prof);
        
        return $this->markdown( 'mail.newLaravelTips', [
            'user' => $this->user
        ]);

    }
}
