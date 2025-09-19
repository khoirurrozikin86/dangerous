<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerahTerima extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = ['no_trx','nik', 'name','department','item_code','item_name','remark'];
}
