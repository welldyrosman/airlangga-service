<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Facilitie extends Model
{
    use HasFactory;

    protected $fillable = [
        'trips_id',
        'facility'
    ];
}
