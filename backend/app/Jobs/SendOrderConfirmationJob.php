<?php

namespace App\Jobs;

use App\Mail\OrderConfirmationMail;
use App\Models\EmailLog;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $backoff = 30;

    public function __construct(private readonly Order $order) {}

    public function handle(): void
    {
        Mail::to($this->order->email)
            ->send(new OrderConfirmationMail($this->order));

        EmailLog::create([
            'to'      => $this->order->email,
            'subject' => 'Your order confirmation #' . $this->order->order_number,
            'type'    => 'order_confirmation',
            'status'  => 'sent',
            'sent_at' => now(),
        ]);
    }

    public function failed(\Throwable $e): void
    {
        EmailLog::create([
            'to'      => $this->order->email,
            'subject' => 'Your order confirmation #' . $this->order->order_number,
            'type'    => 'order_confirmation',
            'status'  => 'failed',
            'error'   => $e->getMessage(),
        ]);
    }
}
