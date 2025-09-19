<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = ['code', 'name','category','posisi','unit','status'];


    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    
}
