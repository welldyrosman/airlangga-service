<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tran extends Model
{
    use HasFactory;
    protected $fillable = [
        'member_id',
        'trip_id',
        'tripdate_id',
        'qty',
        'total',
        'status',
        'user_id'
    ];
}
