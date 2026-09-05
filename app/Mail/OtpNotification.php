<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpNotification extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $code,
        public string $purpose = 'verification',
    ) {
    }

    public function envelope(): Envelope
    {
        $subjects = [
            'voter_registration' => 'Verify your NEC voter registration',
            'voter_account' => 'Verify your NEC voter portal account',
            'login' => 'NEC login verification code',
            'password_reset' => 'NEC password reset code',
        ];
        return new Envelope(
            subject: $subjects[$this->purpose] ?? 'NEC verification code',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.otp',
            with: [
                'code' => $this->code,
                'purpose' => $this->purpose,
            ],
        );
    }
}