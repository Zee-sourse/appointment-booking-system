<?php

use App\Bookings\ScheduleAppointment;
use App\Models\Employee;
use App\Models\Schedule;
use App\Models\ScheduleExclusion;
use App\Models\Service;
use Carbon\Carbon;


it('if the employee availabilty is correct', function () {

    Carbon::setTestNow(Carbon::parse('1st January 2004'));


    $employee = Employee::factory()

    ->has(Schedule::factory()->state([

        'starts_at' => now()->startOfDay(),

        'ends_at' => now()->addYear()->endOfDay()

    ]))
    ->create();


    $service = Service::factory()->create([
        'duration' => 40
    ]);



    $availabilty = (new ScheduleAppointment($employee , $service))

    ->forPeriod( now()->startOfDay() , now()->endOfDay() );


    expect($availabilty->current())

    ->startsAt(now()->setTimeFromTimeString('09:00:00'))

    ->toBeTrue()

    ->endsAt(now()->setTimeFromTimeString('16:20:00'))

    ->toBeTrue();

});




it('it checked the schedule exclusion of employee', function () {

    Carbon::setTestNow(Carbon::parse('1st January 2004'));


    $employee = Employee::factory()

    ->has(Schedule::factory()->state([

        'starts_at' => now()->startOfDay(),

        'ends_at' => now()->addYear()->endOfDay()

    ]))

    ->has(ScheduleExclusion::factory()->state([

        'starts_at' => now()->setTimeFromTimeString('13:00:00'),

        'ends_at' => now()->setTimeFromTimeString('14:00:00')

    ]))

    ->has(ScheduleExclusion::factory()->state([

        'starts_at' => now()->addDay()->startOfDay(),

        'ends_at' => now()->addDay()->endOfDay()

    ]))

    ->create();


    $service = Service::factory()->create([
        'duration' => 40
    ]);



    $availabilty = (new ScheduleAppointment($employee , $service))

    ->forPeriod( now()->startOfDay() , now()->endOfDay() );


    expect($availabilty->current())

    ->startsAt(now()->setTimeFromTimeString('09:00:00'))

    ->toBeTrue()

    ->endsAt(now()->setTimeFromTimeString('13:00:00'))

    ->toBeTrue();


    $availabilty->next();

    expect($availabilty->current())

    ->startsAt(now()->setTimeFromTimeString('14:00:00'))

    ->toBeTrue()

    ->endsAt(now()->setTimeFromTimeString('16:20:00'))

    ->toBeTrue();



    $availabilty->next();


    expect($availabilty->valid())->toBeFalse();


});







it('if the availabilty is in future', function () {

    Carbon::setTestNow(Carbon::parse('1st January 2004 11:00:00'));


    $employee = Employee::factory()

    ->has(Schedule::factory()->state([

        'starts_at' => now()->startOfDay(),

        'ends_at' => now()->addYear()->endOfDay()

    ]))
    ->create();


    $service = Service::factory()->create([
        'duration' => 40
    ]);



    $availabilty = (new ScheduleAppointment($employee , $service))

    ->forPeriod( now()->startOfDay() , now()->endOfDay() );


    expect($availabilty->current())

    ->startsAt(now()->setTimeFromTimeString('12:00:00'))

    ->toBeTrue()

    ->endsAt(now()->setTimeFromTimeString('16:20:00'))

    ->toBeTrue();

});


