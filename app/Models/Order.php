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
        'payment_reference',
        'description',
        'total',
        'currency',
        'order_state',
        'expiration',
        'return_url',
        'process_url',
        'request_id',
    ];

    public function completed(): void
    {
        $this->order_state = 'approved';
        $this->save();
    }
    public function canceled(): void
    {
        $this->order_state = 'reject';
        $this->save();
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function itemCarts(): HasMany
    {
        return $this->hasMany(ItemCart::class, 'itemCart_id');
    }
}
