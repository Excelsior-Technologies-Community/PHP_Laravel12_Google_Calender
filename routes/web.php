<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GoogleCalendarController;

Route::get('/', function () {
    return redirect('/calendar');
});

Route::get('/calendar', [GoogleCalendarController::class, 'index']);

Route::get('/calendar/create', [GoogleCalendarController::class, 'create']);

Route::post('/calendar/store', [GoogleCalendarController::class, 'store']);

Route::get('/calendar/show/{eventId}', [GoogleCalendarController::class, 'show'])
    ->where('eventId', '.*');

Route::get('/calendar/edit/{eventId}', [GoogleCalendarController::class, 'edit'])
    ->where('eventId', '.*');

Route::post('/calendar/update/{eventId}', [GoogleCalendarController::class, 'update'])
    ->where('eventId', '.*');

Route::get('/calendar/delete/{eventId}', [GoogleCalendarController::class, 'delete'])
    ->where('eventId', '.*');
