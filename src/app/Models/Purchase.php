<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    const PAYMENT_METHODS = [
        1 => 'konbini',
        2 => 'card'
    ];

    const PAYMENT_METHOD_LABELS = [
        1 => 'コンビニ払い',
        2 => 'クレジットカード'
    ];

    protected $fillable = [
        'user_id',
        'item_id',
        'payment_method',
        'postal_code',
        'address',
        'building'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
