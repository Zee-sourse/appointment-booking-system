<?php

use App\Bookings\ScheduleAppointment;
use App\Bookings\SlotRangeGenerator;
use App\Bookings\SlotServiceAvailability;
use App\Http\Controllers\ProfileController;
use App\Models\Employee;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/', function () {

    // $employee = Employee::find(1);


    // $service = Service::find(1);



    // $availability = (new ScheduleAppointment($employee,$service));


    // dd($availability->forPeriod(

    //     now()->startOfDay(),

    //     now()->addMonth()->endOfDay()

    // ));



    $employees = Employee::get();

    $service = Service::first();


    $slotServiceAvailablity = (new SlotServiceAvailability($employees ,$service))

    ->forPeriod(now()->startOfDay() , now()->addDay()->endOfDay());


    dd($slotServiceAvailablity);











});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
