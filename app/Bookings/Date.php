<?php


namespace App\Bookings;

use Carbon\Carbon;

class  Date

{
    public $slots;

    public function __construct(protected Carbon $date)
    {
        $this->slots = collect();
    }



    public function addSlot(Slot $slot)
    {

        $this->slots->push($slot);


    }





}

