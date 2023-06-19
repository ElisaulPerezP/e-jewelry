<?php

namespace App\Jobs;

use App\Actions\Orders\CheckOrderStatusAction;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PaymentStatusChecker implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public function __construct()
    {
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $revisor = new CheckOrderStatusAction;
        $orders = Order::where('state', 'pending')->get();
        Log::channel('payments')->info("Command executed");
        foreach ($orders as $order) {
            $revisor->execute($order);
            Log::channel('payments')->info("{$order->id} has been checked, its status has been updated to:{$order->state} Through command.");
        }
    }
}
