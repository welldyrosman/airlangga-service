<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_nm',
        'city',
        'isgroup',
        'min_qty',
        'price',
        'trip_desc',
        'use_mk'
    ];
    public function facilities(){
        return $this->hasMany(Facilitie::class,'trips_id');
    }
    public function images(){
        return $this->hasMany(tripimage::class,'trips_id');
    }
    public function trip_dates(){
        return $this->hasMany(tripdate::class,'trips_id');
    }


}
