<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CloseAccount extends Model
{
    use HasFactory;
    protected $fillable = [
        'userid',
        'email',
        'reason',
        'status'
    ];
}
