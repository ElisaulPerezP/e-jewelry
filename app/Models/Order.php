<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'total',
        'currency',
        'state',
        'return_url',
        'process_url',
        'request_id',
    ];

    public function approved(): void
    {
        $this->state = 'approved';
        $this->save();
    }
    public function rejected(): void
    {
        $this->state = 'rejected';
        $this->save();
    }

    public function setTotal()
    {
        foreach ($this->cartItems as $cartItem) {
            $this->total += $cartItem->amount * $cartItem->product->price;
        }
        $this->save();
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function clone(): self
    {
        $newOrder = $this->replicate(['created_at', 'updated_at']);
        $newOrder->save();

        foreach ($this->cartItems as $cartItem) {
            $newCartItem = $cartItem->replicate();
            $newCartItem->order_id = $newOrder->id;
            $newCartItem->save();
        }

        return $newOrder;
    }
}
