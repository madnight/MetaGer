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
    public function __construct($from, $message, $name)
    {
        $this->subject = "MetaGer - Spende";
        $this->reply = $from;
        $this->message = $message;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->reply, $this->name)
            ->subject($this->subject)
            ->text('kontakt.mail')
            ->with('messageText', $this->message);
    }
}
