<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Google_Service_Calendar_Event;
use Google_Service_Calendar_EventDateTime;
use Google_Service_Calendar_EventAttendee;
use Google_Service_Calendar_ConferenceData;
use Google_Service_Calendar_EventReminders;

class GoogleCalendarController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subDays(30);
        $endDate = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now()->addDays(60);
        
        $events = Event::get($startDate, $endDate);
        
        if ($request->category) {
            $events = collect($events)->filter(function ($event) use ($request) {
                $description = json_decode($event->description ?? '{}', true);
                return ($description['category'] ?? '') === $request->category;
            });
        }
        
        if ($request->keyword) {
            $events = collect($events)->filter(function ($event) use ($request) {
                return stripos($event->name, $request->keyword) !== false ||
                       stripos($event->description ?? '', $request->keyword) !== false;
            });
        }
        
        $events = collect($events)->sortBy('startDateTime');
        
        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 10;
        
        $paginated = new LengthAwarePaginator(
            $events->forPage($page, $perPage)->values(),
            $events->count(),
            $perPage,
            $page,
            ['path' => request()->url(), 'query' => request()->query()]
        );
        
        return view('calendar.index', compact('paginated'));
    }
    
    public function calendarView(Request $request)
    {
        $view = $request->view ?? 'month';
        $date = $request->date ? Carbon::parse($request->date) : Carbon::now();
        
        return view('calendar.calendar', compact('view', 'date'));
    }
    
    public function getEvents(Request $request)
    {
        $start = Carbon::parse($request->start);
        $end = Carbon::parse($request->end);
        $events = Event::get($start, $end);
        
        $formattedEvents = [];
        foreach ($events as $event) {
            $description = json_decode($event->description ?? '{}', true);
            $formattedEvents[] = [
                'id' => $event->id,
                'title' => $event->name,
                'start' => $event->startDateTime->toRfc3339String(),
                'end' => $event->endDateTime->toRfc3339String(),
                'color' => $description['color'] ?? '#3788d8',
                'description' => $description['description'] ?? '',
                'category' => $description['category'] ?? 'general',
                'status' => $description['status'] ?? 'confirmed'
            ];
        }
        
        return response()->json($formattedEvents);
    }
    
    public function create()
    {
        return view('calendar.create');
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'start' => 'required',
            'end' => 'required',
            'category' => 'required',
            'description' => 'nullable',
        ]);
        
        $metadata = [
            'description' => $request->description,
            'category' => $request->category,
            'color' => $request->color_id ?? $request->color ?? '#3788d8',
            'status' => $request->status ?? 'confirmed',
            'reminders' => $request->reminders ?? [],
            'attendees' => explode(',', $request->attendees ?? ''),
            'attachments' => $request->attachments ?? []
        ];
        
        $event = new Event();
        $event->name = $request->title;
        $event->startDateTime = Carbon::parse($request->start);
        $event->endDateTime = Carbon::parse($request->end);
        $event->description = json_encode($metadata);
        
        if ($request->color_id) {
            $event->googleEvent->setColorId($request->color_id);
        }

        if ($request->attendees) {
            $attendeeList = [];
            $emails = explode(',', $request->attendees);
            foreach ($emails as $email) {
                $cleanEmail = trim($email);
                if (!empty($cleanEmail) && filter_var($cleanEmail, FILTER_VALIDATE_EMAIL)) {
                    $attendeeList[] = new \Google_Service_Calendar_EventAttendee(['email' => $cleanEmail]);
                }
            }
            if (!empty($attendeeList)) {
                $event->googleEvent->setAttendees($attendeeList);
            }
        }

        if ($request->reminder_minutes) {
            $reminders = new Google_Service_Calendar_EventReminders([
                'useDefault' => false,
                'overrides' => [
                    ['method' => 'popup', 'minutes' => (int) $request->reminder_minutes]
                ]
            ]);
            $event->googleEvent->setReminders($reminders);
        } elseif ($request->has_reminder) {
            $event->addReminder(30, 'popup');
            $event->addReminder(60, 'email');
        }
        
        if ($request->recurring === 'yes') {
            $event->setRecurrence([
                'RRULE:FREQ=' . ($request->recurrence_freq ?? 'WEEKLY') . 
                ';COUNT=' . ($request->recurrence_count ?? 10)
            ]);
        }
        
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('event-attachments', 'public');
            $metadata['attachments'][] = $path;
            $event->description = json_encode($metadata);
        }
        
        if ($request->has('create_meet')) {
            $conferenceData = new Google_Service_Calendar_ConferenceData([
                'createRequest' => [
                    'requestId' => uniqid(),
                    'conferenceSolutionKey' => ['type' => 'hangoutsMeet']
                ]
            ]);
            $event->googleEvent->setConferenceData($conferenceData);
        }

        $event->save();
        
        return redirect('/calendar')->with('success', 'Event created successfully!');
    }
    
    public function show($eventId)
    {
        $event = Event::find($eventId);
        if (!$event) {
            abort(404);
        }
        
        $metadata = json_decode($event->description ?? '{}', true);
        
        return view('calendar.show', compact('event', 'metadata'));
    }
    
    public function edit($eventId)
    {
        $event = Event::find($eventId);
        if (!$event) {
            abort(404);
        }
        
        $metadata = json_decode($event->description ?? '{}', true);
        
        return view('calendar.edit', compact('event', 'metadata'));
    }
    
    public function update(Request $request, $eventId)
    {
        $event = Event::find($eventId);
        if (!$event) {
            abort(404);
        }
        
        $metadata = json_decode($event->description ?? '{}', true);
        $metadata['category'] = $request->category ?? $metadata['category'] ?? 'general';
        $metadata['description'] = $request->description ?? '';
        $metadata['status'] = $request->status ?? 'confirmed';
        
        $event->name = $request->title;
        $event->startDateTime = Carbon::parse($request->start);
        $event->endDateTime = Carbon::parse($request->end);
        $event->description = json_encode($metadata);
        
        $event->save();
        
        return redirect('/calendar')->with('success', 'Event updated successfully!');
    }

    public function updateEventDate(Request $request, $eventId)
    {
        $event = Event::find($eventId);
        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }
        
        $event->startDateTime = Carbon::parse($request->start);
        $event->endDateTime = Carbon::parse($request->end);
        $event->save();
        
        return response()->json(['success' => 'Event updated successfully']);
    }
    
    public function delete($eventId)
    {
        $event = Event::find($eventId);
        if (!$event) {
            abort(404);
        }
        
        $event->delete();
        
        return redirect('/calendar')->with('success', 'Event deleted successfully!');
    }
    
    public function export(Request $request)
    {
        $events = Event::get();
        
        $filename = 'events-' . date('Y-m-d') . '.csv';
        $handle = fopen('php://temp', 'w+');
        
        fputcsv($handle, ['Title', 'Start Date', 'End Date', 'Description', 'Category']);
        
        foreach ($events as $event) {
            $metadata = json_decode($event->description ?? '{}', true);
            fputcsv($handle, [
                $event->name,
                $event->startDateTime->format('Y-m-d H:i:s'),
                $event->endDateTime->format('Y-m-d H:i:s'),
                $metadata['description'] ?? '',
                $metadata['category'] ?? 'general'
            ]);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);
        
        return response($csv, 200)
            ->header('Content-Type', 'application/csv')
            ->header('Content-Disposition', "attachment; filename=$filename");
    }
    
    public function copy($eventId)
    {
        $originalEvent = Event::find($eventId);
        if (!$originalEvent) {
            abort(404);
        }
        
        $newEvent = new Event();
        $newEvent->name = $originalEvent->name . ' (Copy)';
        $newEvent->startDateTime = Carbon::parse($originalEvent->startDateTime)->addDay();
        $newEvent->endDateTime = Carbon::parse($originalEvent->endDateTime)->addDay();
        $newEvent->description = $originalEvent->description;
        $newEvent->save();
        
        return redirect('/calendar')->with('success', 'Event copied successfully!');
    }
    
    public function upcoming()
    {
        $events = Event::get(Carbon::now(), Carbon::now()->addDays(7));
        return view('calendar.upcoming', compact('events'));
    }
}