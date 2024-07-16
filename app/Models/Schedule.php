<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    use HasFactory;


    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
    ];


    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }


    public function getDayHoursFromComingDay(Carbon $day){
        $hours =
        array_filter( [
            $this->{strtolower($day->format('l')) .''. '_starts_at' },
            $this->{strtolower($day->format('l')) .''. '_ends_at' },
        ]);

        return empty($hours) ? null : $hours;

    }




}
