<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'id_product',
        'amount',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'producto_id');
    }
}
