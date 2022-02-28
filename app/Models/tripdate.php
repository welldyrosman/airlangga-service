<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tripdate extends Model
{
    use HasFactory;
    protected $table = 'tripdates';
    protected $fillable = [
        'trips_id',
        'trip_date',
    ];
}
