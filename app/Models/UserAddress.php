<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'email', 'address', 'city', 'state', 'zip', 'sameadr'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
