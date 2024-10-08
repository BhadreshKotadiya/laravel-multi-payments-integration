<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_address_id',
        'total',
        'status',
        'session_id',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
