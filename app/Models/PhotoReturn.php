<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'photo1',
        'photo2',
        'photo3',
        'photo4',
        'photo5',
        'department',
        'creator',
        'remark',
    ];
}
