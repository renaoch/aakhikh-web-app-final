<?php

namespace App\Jobs;

use App\Mail\DonationReceiptMail;
use App\Models\Donation;
use App\Models\EmailLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendDonationReceiptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;

    public function __construct(private readonly Donation $donation) {}

    public function handle(): void
    {
        Mail::to($this->donation->email)
            ->send(new DonationReceiptMail($this->donation));

        EmailLog::create([
            'to'      => $this->donation->email,
            'subject' => 'Thank you for your donation',
            'type'    => 'donation_receipt',
            'status'  => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function failed(\Throwable $e): void
    {
        EmailLog::create([
            'to'      => $this->donation->email,
            'subject' => 'Thank you for your donation',
            'type'    => 'donation_receipt',
            'status'  => 'failed',
            'error'   => $e->getMessage(),
        ]);
    }
}
