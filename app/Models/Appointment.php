<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime'
    ];


    public static function booted(){

        static::creating(function (Appointment $appointment){

            $appointment->uuid = str()->uuid();

        });

    }
}
