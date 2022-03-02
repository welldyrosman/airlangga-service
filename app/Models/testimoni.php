<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class testimoni extends Model
{
    use HasFactory;
    protected $table = 'testimonies';
    protected $fillable = [
        'nama',
        'asal',
        'testimoni',
        'photo',
    ];
}
