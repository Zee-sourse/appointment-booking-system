<?php


namespace App\Bookings;

use App\Models\Employee;
use App\Models\ScheduleExclusion;
use App\Models\Service;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Spatie\Period\Boundaries;
use Spatie\Period\Period;
use Spatie\Period\PeriodCollection;
use Spatie\Period\Precision;

class ScheduleAppointment
{

    protected PeriodCollection $periods;

    public function __construct(protected Employee $employee, protected Service $service)
    {

        $this->periods = new PeriodCollection();
    }


    public function forPeriod(Carbon $startDay, Carbon $endDay)
    {
        collect(CarbonPeriod::create($startDay, $endDay)->days())
            ->each(function ($day) {

                $this->addAvailabilityFromComingDay($day);


                $this->employee->scheduleExclusions->each(function (ScheduleExclusion $exclustion) {

                    $this->subtractAvailabilityFromDay($exclustion);
                });

                $this->updatingTimeToGenericTime();
            });

        return $this->periods;
    }

    protected function addAvailabilityFromComingDay($day)
    {

        $schedule = $this->employee->schedules->where('starts_at', '<=', $day)
            ->where('ends_at', '>=', $day)->first();

        if (!$schedule) {
            return;
        }

        if (![$startsAt, $endsAt] =   $schedule->getDayHoursFromComingDay($day)) {
            return;
        }

        $this->periods = $this->periods->add(

            Period::make(

                $day->copy()->setTimeFromTimeString($startsAt),
                $day->copy()->setTimeFromTimeString($endsAt)->subMinute($this->service->duration),

                Precision::MINUTE()
            )



        );
    }

    protected function subtractAvailabilityFromDay(ScheduleExclusion $exclusion)
    {
        $this->periods = $this->periods->subtract(

            Period::make(

                $exclusion->starts_at,

                $exclusion->ends_at,

                Precision::MINUTE(),

                Boundaries::EXCLUDE_ALL()

            )

        );
    }

    protected function updatingTimeToGenericTime()
    {


        $this->periods = $this->periods->subtract(

            Period::make(
                now()->startOfDay(),
                now()->endOfHour(),
                Precision::MINUTE(),
                Boundaries::EXCLUDE_START()
            )
        );
    }
}
