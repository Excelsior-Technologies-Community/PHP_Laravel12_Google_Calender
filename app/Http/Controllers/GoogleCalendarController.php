<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class GoogleCalendarController extends Controller
{

    /**
     * Display all Google Calendar events
     */
 public function index(Request $request)
{
    $events = Event::get();

    // SEARCH
    if ($request->keyword) {
        $events = collect($events)->filter(function ($event) use ($request) {
            return stripos($event->name, $request->keyword) !== false;
        });
    }

    // PAGINATION
    $page = LengthAwarePaginator::resolveCurrentPage();
    $perPage = 3;

    $paginated = new LengthAwarePaginator(
        collect($events)->forPage($page, $perPage)->values(),
        collect($events)->count(),
        $perPage,
        $page,
        [
            'path' => request()->url(),
            'query' => request()->query(),
        ]
    );

    return view('calendar.index', [
        'events' => $paginated   // ✅ IMPORTANT FIX
    ]);
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
