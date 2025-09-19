<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Request;


class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjamans';

    protected $guarded = [];

    protected $fillable = ['no_trx_out','employee_id', 'no_trx_return','item_id','remark'];

    
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
    
    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

  
}
