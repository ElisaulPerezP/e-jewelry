<?php

namespace App\Models;

//use App\Jobs\MailerOrderState;
use App\Actions\CartItem\ChangeCartItemStateAction;
use App\Http\Requests\CartItem\StateCartItemRequest;
use App\Jobs\MailerOrderState;
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
        MailerOrderState::dispatch($this)->onConnection('database')->onQueue('mailer');
        $this->cartItems->each(function ($item) {
            $action = new ChangeCartItemStateAction();
            $action->execute(new StateCartItemRequest(['state' => 'paid']), $item);
        });
        //TODO: delegar esta logica
    }
    public function rejected(): void
    {
        $this->state = 'rejected';
        $this->save();
        MailerOrderState::dispatch($this)->onConnection('database')->onQueue('mailer');
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
            $newCartItem->save(); //TODO: aqui hay fuga de stock
        }

        return $newOrder;
    }
}
