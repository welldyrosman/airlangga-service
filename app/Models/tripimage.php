<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tripimage extends Model
{
    use HasFactory;
    protected $fillable = [
        'trips_id',
        'file_nm',
        'url',
    ];
}
