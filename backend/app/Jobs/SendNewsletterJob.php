<?php

namespace App\Jobs;

use App\Mail\NewsletterMail;
use App\Models\EmailLog;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendNewsletterJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(
        private readonly string $subject,
        private readonly string $htmlContent,
        private readonly Subscriber $subscriber,
    ) {}

    public function handle(): void
    {
        Mail::to($this->subscriber->email)
            ->send(new NewsletterMail($this->subject, $this->htmlContent));

        EmailLog::create([
            'to'         => $this->subscriber->email,
            'subject'    => $this->subject,
            'type'       => 'newsletter',
            'status'     => 'sent',
            'sent_at'    => now(),
        ]);
    }

    public function failed(\Throwable $e): void
    {
        EmailLog::create([
            'to'         => $this->subscriber->email,
            'subject'    => $this->subject,
            'type'       => 'newsletter',
            'status'     => 'failed',
            'error'      => $e->getMessage(),
        ]);
    }
}
