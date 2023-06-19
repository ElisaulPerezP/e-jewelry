<?php

namespace App\Jobs;

use App\Mail\OrderStateMailer;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailerOrderState implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::channel('mailer')->info("User: {$this->order->user->id}, email sent whit order state, it was changed to {$this->order->state}");
        Mail::to($this->order->user->email)->send(new OrderStateMailer($this->order));
    }
}
