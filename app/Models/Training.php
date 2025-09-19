<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Training extends Model
{
    use HasFactory;

    protected $fillable = ['training_no', 'basicoperation_id','employee_id','remark','user_id'];


    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function basicoperation(): BelongsTo
    {
        return $this->belongsTo(BasicOperation::class);
    }
}
