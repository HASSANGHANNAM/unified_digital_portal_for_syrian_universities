<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class VerifyEmailMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */

    public $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function build()
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify', // اسم الراوت
            Carbon::now()->addMinutes(60), // صلاحية الرابط
            ['id' => $this->user->id]
        );

        return $this->subject('Verify Your Email')
            ->view('emails.verify-email')
            ->with([
                'url' => $verificationUrl
            ]);
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'view.name',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
