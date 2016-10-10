<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Spende extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($from, $message)
    {
        $this->subject = "MetaGer - Spende";
        $this->reply   = $from;
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->reply)
            ->subject($this->subject)
            ->text('kontakt.mail')
            ->with('messageText', $this->message);
    }
}
