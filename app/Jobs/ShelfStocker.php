<?php

namespace App\Jobs;

use App\Models\CartItem;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ShelfStocker implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    private CartItem $CartItem;
    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }
    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $cartItems = CartItem::where('amount', '>', 1)
            ->whereIn('state', ['in_cart', 'selected', 'in_order'])
            ->where('updated_at', '<', Carbon::now()->addHour())
            ->get();
        Log::channel('stocker')->info('Command executed');
        foreach ($cartItems as $item) {
            Log::channel('stocker')->info("{$item->amount} units of CartItem id: {$item->id} moved to the stock of product id: {$item->product->id}");
            $item->product->stock += $item->amount;
            $item->product->save();
            $item->state = 'collected';
            $item->save();
        }
    }
}
