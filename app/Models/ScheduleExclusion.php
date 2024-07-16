<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleExclusion extends Model
{
    use HasFactory;


    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime'
    ];
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }



}
