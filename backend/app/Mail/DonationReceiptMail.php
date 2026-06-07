<?php

namespace App\Mail;

use App\Models\Donation;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DonationReceiptMail extends Mailable
{
    use SerializesModels;

    public function __construct(public readonly Donation $donation) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Thank you for your donation');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.donation-receipt');
    }
}
