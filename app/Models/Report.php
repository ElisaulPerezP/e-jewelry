<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_path',
        'user_id',
    ];

    public function user()
    {
        $this->belongsTo(User::class);
    }
}
