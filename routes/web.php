<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleCalendarController;

Route::get('/', function () {
    return redirect('/calendar');
});

// Main CRUD routes
Route::get('/calendar', [GoogleCalendarController::class, 'index'])->name('calendar.index');
Route::get('/calendar/create', [GoogleCalendarController::class, 'create'])->name('calendar.create');
Route::post('/calendar/store', [GoogleCalendarController::class, 'store'])->name('calendar.store');
Route::get('/calendar/show/{eventId}', [GoogleCalendarController::class, 'show'])->where('eventId', '.*')->name('calendar.show');
Route::get('/calendar/edit/{eventId}', [GoogleCalendarController::class, 'edit'])->where('eventId', '.*')->name('calendar.edit');
Route::post('/calendar/update/{eventId}', [GoogleCalendarController::class, 'update'])->where('eventId', '.*')->name('calendar.update');
Route::get('/calendar/delete/{eventId}', [GoogleCalendarController::class, 'delete'])->where('eventId', '.*')->name('calendar.delete');

// New advanced routes
Route::get('/calendar/calendar-view', [GoogleCalendarController::class, 'calendarView'])->name('calendar.view');
Route::get('/calendar/events', [GoogleCalendarController::class, 'getEvents'])->name('calendar.events');
Route::get('/calendar/export/csv', [GoogleCalendarController::class, 'export'])->name('calendar.export');
Route::get('/calendar/copy/{eventId}', [GoogleCalendarController::class, 'copy'])->where('eventId', '.*')->name('calendar.copy');
Route::get('/calendar/upcoming', [GoogleCalendarController::class, 'upcoming'])->name('calendar.upcoming');

Route::post('/calendar/update-date/{id}', [App\Http\Controllers\GoogleCalendarController::class, 'updateEventDate']);
Route::get('/test', function() {
    try {
        $events = \Spatie\GoogleCalendar\Event::get();
        return " Working! Found " . count($events) . " events";
    } catch (\Exception $e) {
        return " Error: " . $e->getMessage();
    }
});