<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkout extends Model
{
    use HasFactory;
    protected $fillable = [
        'orderid',
        'userid',
        'address',
        'phone',
        'item_id',
        'price',
        'quantity',
        'preference',
        'ordertype',
        'payment_method',
        'transaction_reference',
        'status',
        'is_paid'
    ];
}
