<?php


namespace App\Bookings;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class SlotRangeGenerator

{

    public function __construct(protected Carbon $startsAt , protected Carbon $endsAt)
    {
        //
    }


    public function generate(int $interval)
    {

        $collection = collect();


        $days = CarbonPeriod::create($this->startsAt , '1 day' , $this->endsAt);


        foreach ($days as $key => $day) {


            $date = new Date($day);

            $times = CarbonPeriod::create($day->startOfDay() ,sprintf('%d minutes' , $interval), $day->copy()->endOfDay());

            foreach ($times as $key => $time) {

                $date->addSlot(new Slot($time));

            }

            $collection->push($date);

        }
        return $collection;


    }


}
