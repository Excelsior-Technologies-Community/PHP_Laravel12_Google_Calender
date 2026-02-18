<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;

class GoogleCalendarController extends Controller
{

    /**
     * Display all Google Calendar events
     */
    public function index()
    {
        // Get all events
        $events = Event::get();

        // Return view
        return view('calendar.index', compact('events'));
    }


    /**
     * Show create event form
     */
    public function create()
    {
        return view('calendar.create');
    }


    /**
     * Store new event in Google Calendar
     */
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'title' => 'required',
            'start' => 'required',
            'end' => 'required',
        ]);

        // Create event
        Event::create([
            'name' => $request->title,
            'startDateTime' => Carbon::parse($request->start),
            'endDateTime' => Carbon::parse($request->end),
        ]);

        // Redirect
        return redirect('/calendar')->with('success', 'Event created successfully');
    }


    /**
     * Show single event
     */
    // Show event
    public function show($eventId)
    {
        $event = Event::find($eventId);

        if (!$event) {
            abort(404);
        }

        return view('calendar.show', compact('event'));
    }


    // Edit event
    public function edit($eventId)
    {
        $event = Event::find($eventId);

        if (!$event) {
            abort(404);
        }

        return view('calendar.edit', compact('event'));
    }


    // Update event
    public function update(Request $request, $eventId)
    {
        $event = Event::find($eventId);

        if (!$event) {
            abort(404);
        }

        $event->name = $request->title;
        $event->startDateTime = Carbon::parse($request->start);
        $event->endDateTime = Carbon::parse($request->end);

        $event->save();

        return redirect('/calendar');
    }


    // Delete event
    public function delete($eventId)
    {
        $event = Event::find($eventId);

        if (!$event) {
            abort(404);
        }

        $event->delete();

        return redirect('/calendar');
    }


}
