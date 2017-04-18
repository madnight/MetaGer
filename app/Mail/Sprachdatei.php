<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Sprachdatei extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message, $fileContent, $filename, $replyAddress = "noreply@metager.de")
    {
        $this->subject     = "MetaGer - Sprachdatei";
        $this->reply       = $replyAddress;
        $this->message     = $message;
        $this->fileContent = $fileContent;
        $this->filename    = $filename;
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
            ->with('messageText', $this->message)
            ->attachData($this->fileContent, $this->filename);
    }
}
