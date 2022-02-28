<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ourservice extends Model
{
    use HasFactory;
    protected $fillable = [
        'serv_nm',
        'serv_desc',
        'seq',
        'use_mk'
    ];
}
