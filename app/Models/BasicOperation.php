<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BasicOperation extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = ['op_code', 'op_name','op_type','remark'];

    public function training(): HasMany
    {
        return $this->hasMany(Training::class);
    }
}
