<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Kontakt extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $from, $subject, $message)
    {
        $this->name = $name;
        $this->reply   = $from;
        $this->subject = $subject;
        $this->message = $message;
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
