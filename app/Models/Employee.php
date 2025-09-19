<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employee extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = ['nik', 'name','department','posisi'];


    public function transaction(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class);
    }

    public function training(): HasMany
    {
        return $this->hasMany(Training::class);
    }
}
