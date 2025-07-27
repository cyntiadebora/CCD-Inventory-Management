<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $request;
    public $adminMessage;

    /**
     * Create a new message instance.
     */
   public function __construct($user, $request, $adminMessage)
    {
        $this->user = $user;
        $this->request = $request;
        $this->adminMessage = $adminMessage;
    }

    /**
     * Build the message.
     */
    public function build()
        {
            return $this->subject('Your Request Has Been Approved')
                        ->replyTo('staffinventory123@gmail.com', 'Admin CCD') // ← tambahkan ini
                        ->view('emails.request-approved')
                        ->with([
                            'user' => $this->user,
                            'request' => $this->request,
                            'adminMessage' => $this->adminMessage,
                        ]);
        }
}
