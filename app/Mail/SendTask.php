<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendTask extends Mailable
{
    use Queueable, SerializesModels;

    public $emails;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($emails)
    {
        $this->emails = $emails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from($address = 'system@dream-homeseller.com', $name = 'Task Management')
            ->subject('Task: '.$this->emails['title'])
            ->view('emails.sendmail');
    }
}
