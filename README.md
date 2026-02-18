# PHP_Laravel12_Google_Calander


## Project Description

PHP_Laravel12_Google_Calander is a Laravel 12 web application that demonstrates how to integrate Google Calendar using the Spatie Laravel Google Calendar package.

This project allows users to perform complete CRUD (Create, Read, Update, Delete) operations on Google Calendar events directly from a Laravel application.

Instead of storing events in a local database, all events are stored in the user's real Google Calendar using secure Google Service Account authentication.



## Project Features

• View all Google Calendar events  
• Create new event  
• Edit event  
• Show event details  
• Delete event  
• Secure Google API integration using Service Account  
• Clean UI using Blade



## Requirements

• PHP 8.2 or higher  
• Composer  
• Laravel 12  
• MySQL  
• Google Account  
• Google Cloud Project


---



## Installation Steps


---


## STEP 1: Create Laravel 12 Project

### Open terminal / CMD and run:

```
composer create-project laravel/laravel PHP_Laravel12_Google_Calender "12.*"

```

### Go inside project:

```
cd PHP_Laravel12_Google_Calender

```

#### Explanation:

This command creates a new Laravel 12 project folder with all required Laravel files.




## STEP 2: Database Setup 

### Open .env and set:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_google_calender
DB_USERNAME=root
DB_PASSWORD=

```

### Create database in MySQL / phpMyAdmin:

```
Database name: laravel12_google_calender

```

#### Explanation:

We configure Laravel to connect to MySQL database where posts and slugs will be stored.



## STEP 3: Install  Package

### Run:

```
composer require spatie/laravel-google-calendar

php artisan vendor:publish --provider="Spatie\GoogleCalendar\GoogleCalendarServiceProvider"


```



## STEP 4: Folder Setup

### Create folder:

```
storage/app/google-calendar/

```

### Put your JSON file:

```
storage/app/google-calendar/service-account-credentials.json

```



## STEP 5: Create Google Cloud Service Account & Download JSON

This step connects Laravel with Google Calendar using the official Google API.


### 5.1 Open Google Cloud Console

1. Open this website in your browser:

```
https://console.cloud.google.com/

```

2. Login with your Google account.



### 5.2 Create New Project

1. Click:

```
Select Project → New Project

```

2. Enter:

```
Project Name: Laravel Google Calendar

```

3. Click:

```
Create

```


### 5.3 Enable Google Calendar API

1. Click:

```
APIs & Services → Library

```
2. Search:

```
Google Calendar API

```

3. Click:

```
Enable

```

#### Explanation:

This allows your Laravel application to access Google Calendar.



### 5.4 Create Service Account

1. Click:

```
APIs & Services → Credentials

```

2. Click:

```
Create Credentials → Service Account

```

3. Fill:

```
Service Account Name: laravel-google-calendar

```

4. Click:

```
Create and Continue

```

5. Click:

```
Done

```


### 5.5 Create JSON Key File

1. Click your Service Account from list.

2. Go to:

```
Keys tab

```

3. Click:

```
Add Key → Create New Key

```

4. Select:

```
JSON

```

5. Click:

```
Create

```

6. JSON file will download automatically.

#### Example filename:

- laravel-google-calendar-123456.json



### 5.6 Move JSON File to Laravel Project

1. Copy JSON file into:

```
storage/app/google-calendar/

```

2. Rename file to:

```
service-account-credentials.json

```

3. Final path:

```
storage/app/google-calendar/service-account-credentials.json

```


### 5.7 Share Your Google Calendar With Service Account

1. Open Google Calendar:

```
https://calendar.google.com/

```

2. Click:

```
Settings → Your Calendar → Share with specific people

```

3. Copy your service account email from JSON file:

#### Example:

```
laravel-google-calendar@project-id.iam.gserviceaccount.com

```

4. Paste email and give permission:

```
Make changes to events

```

5. Click:

```
Save

```


### 5.8 Open .env and Add:

```
GOOGLE_CALENDAR_ID=primary
GOOGLE_CALENDAR_AUTH_PROFILE=service_account

```




## STEP 6: config/google-calendar.php FULL CODE

### File: config/google-calendar.php

```
<?php

return [

    'default_auth_profile' => 'service_account',

    'auth_profiles' => [

        'service_account' => [

            /*
             * path to service account credentials json file
             */
            'credentials_json' => storage_path('app/google-calendar/service-account-credentials.json'),

        ],

    ],

    /*
     * Google Calendar ID
     */
    'calendar_id' => env('GOOGLE_CALENDAR_ID'),

];

```




## STEP 7: Controller FULL CODE

### Create controller:

```
php artisan make:controller GoogleCalendarController

```

### File: app/Http/Controllers/GoogleCalendarController.php

```
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

```



## STEP 8: routes/web.php FULL CODE

### File: routes/web.php

```
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

```




## STEP 9: View Files

### Create folder:

```
resources/views/calendar/

```

### resources/views/calendar/index.blade.php

```
<!DOCTYPE html>
<html>

<head>
    <title>Google Calendar Events</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f7fc;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 90%;
            max-width: 1100px;
            margin: 40px auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        h2 {
            margin-bottom: 20px;
        }

        .btn {
            text-decoration: none;
            padding: 10px 18px;
            border-radius: 6px;
            color: white;
            font-size: 14px;
        }

        .btn-create {
            background: #28a745;
        }

        .btn-show {
            background: #17a2b8;
        }

        .btn-edit {
            background: #ffc107;
            color: black;
        }

        .btn-delete {
            background: #dc3545;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th {
            background: #343a40;
            color: white;
            padding: 12px;
        }

        table td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
        }

        table tr:hover {
            background: #f1f1f1;
        }

        .actions a {
            margin-right: 5px;
        }
    </style>

</head>

<body>

    <div class="container">

        <h2>📅 Google Calendar Events</h2>

        <a href="/calendar/create" class="btn btn-create">
            + Create Event
        </a>

        <br><br>

        <table>

            <tr>
                <th>Title</th>
                <th>Start</th>
                <th>End</th>
                <th>Action</th>
            </tr>

            @foreach($events as $event)

                <tr>

                    <td>{{ $event->name }}</td>

                    <td>{{ $event->startDateTime }}</td>

                    <td>{{ $event->endDateTime }}</td>

                    <td class="actions">

                        <a href="{{ url('/calendar/show/' . $event->id) }}" class="btn btn-show">
                            Show
                        </a>

                        <a href="{{ url('/calendar/edit/' . $event->id) }}" class="btn btn-edit">
                            Edit
                        </a>

                        <a href="{{ url('/calendar/delete/' . $event->id) }}" class="btn btn-delete"
                            onclick="return confirm('Are You Sure?')">
                            Delete
                        </a>

                    </td>


                </tr>

            @endforeach

        </table>

    </div>

</body>

</html>

```


### resources/views/calendar/create.blade.php

```
<!DOCTYPE html>
<html>

<head>
    <title>Create Event</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f7fc;
        }

        .container {
            width: 400px;
            margin: 60px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            margin-bottom: 15px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        button {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 6px;
            font-size: 16px;
        }

        button:hover {
            background: #218838;
        }

        a {
            text-decoration: none;
        }
    </style>

</head>

<body>

    <div class="container">

        <h2>Create Event</h2>

        <form method="POST" action="/calendar/store">

            @csrf

            Title:
            <input type="text" name="title" required>

            Start:
            <input type="datetime-local" name="start" required>

            End:
            <input type="datetime-local" name="end" required>

            <button type="submit">
                Create Event
            </button>

            <br><br>

            <a href="/calendar">← Back</a>

        </form>

    </div>

</body>

</html>

```


### resources/views/calendar/show.blade.php

```
<!DOCTYPE html>
<html>

<head>
    <title>Show Event</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f7fc;
        }

        .container {
            width: 400px;
            margin: 60px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .label {
            font-weight: bold;
        }
    </style>

</head>

<body>

    <div class="container">

        <h2>Event Details</h2>

        <p>
            <span class="label">Title:</span>
            {{ $event->name }}
        </p>

        <p>
            <span class="label">Start:</span>
            {{ $event->startDateTime }}
        </p>

        <p>
            <span class="label">End:</span>
            {{ $event->endDateTime }}
        </p>

        <br>

        <a href="/calendar">← Back</a>

    </div>

</body>

</html>

```


### resources/views/calendar/edit.blade.php

```
<!DOCTYPE html>
<html>

<head>
    <title>Edit Event</title>

    <style>
        body {
            font-family: Arial;
            background: #f4f7fc;
        }

        .container {
            width: 400px;
            margin: 60px auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
        }

        button {
            background: #ffc107;
            border: none;
            padding: 10px;
            width: 100%;
            border-radius: 6px;
        }
    </style>

</head>

<body>

    <div class="container">

        <h2>Edit Event</h2>

        <form method="POST" action="/calendar/update/{{ $event->id }}">

            @csrf

            Title:
            <input type="text" name="title" value="{{ $event->name }}" required>

            Start:
            <input type="datetime-local" name="start"
                value="{{ \Carbon\Carbon::parse($event->startDateTime)->format('Y-m-d\TH:i') }}" required>

            End:
            <input type="datetime-local" name="end"
                value="{{ \Carbon\Carbon::parse($event->endDateTime)->format('Y-m-d\TH:i') }}" required>

            <button type="submit">
                Update Event
            </button>

            <br><br>

            <a href="/calendar">← Back</a>

        </form>

    </div>

</body>

</html>

```



## ## STEP 10: Launch the Server

### Run:

```
php artisan serve

```
### Then open your browser:

```
http://127.0.0.1:8000/calendar

```


## So you can see this type Output:


### Event List Page:


<img width="1917" height="847" alt="Screenshot 2026-02-18 115745" src="https://github.com/user-attachments/assets/3d1a3ce1-6ecd-45d3-9782-543ee58e9a3c" />


### Create Event Page:


<img width="1919" height="907" alt="Screenshot 2026-02-18 120024" src="https://github.com/user-attachments/assets/92673c74-f0dd-4c62-8617-7cda62b41d18" />

<img width="1919" height="877" alt="Screenshot 2026-02-18 122755" src="https://github.com/user-attachments/assets/4e26b2d9-b508-4997-a644-d4c9d0d7bee7" />


### Show Event Page:


<img width="1919" height="957" alt="Screenshot 2026-02-18 123505" src="https://github.com/user-attachments/assets/16d4fbae-0649-4070-a534-38e1538dbb5c" />


### Edit Event Page:


<img width="1919" height="921" alt="Screenshot 2026-02-18 123547" src="https://github.com/user-attachments/assets/7c127c56-ff74-44e2-93c7-a19d67de20c2" />

<img width="1919" height="880" alt="Screenshot 2026-02-18 123605" src="https://github.com/user-attachments/assets/dc9b5348-b40a-43d6-99b6-4f0954bc26e1" />


### Delete Event:


<img width="1917" height="920" alt="Screenshot 2026-02-18 123651" src="https://github.com/user-attachments/assets/3e19f8f5-3b19-4070-ad5b-877faf00c988" />



---


# Project Folder Structure:

```
PHP_Laravel12_Google_Calander/
│
├── app/
│   ├── Http/
│   │   └── Controllers/
│   │       └── GoogleCalendarController.php
│   │
│   ├── Models/
│   │
│   └── Providers/
│
├── bootstrap/
│
├── config/
│   ├── app.php
│   ├── database.php
│   └── google-calendar.php
│
├── database/
│   ├── factories/
│   ├── migrations/
│   └── seeders/
│
├── public/
│   ├── index.php
│   └── .htaccess
│
├── resources/
│   ├── views/
│   │   └── calendar/
│   │       ├── index.blade.php
│   │       ├── create.blade.php
│   │       ├── show.blade.php
│   │       └── edit.blade.php
│   │
│   ├── css/
│   ├── js/
│   └── images/
│
├── routes/
│   ├── web.php
│   └── console.php
│
├── storage/
│   ├── app/
│   │   └── google-calendar/
│   │       └── service-account-credentials.json
│   │
│   ├── framework/
│   └── logs/
│
├── tests/
│
├── vendor/
│
├── .env
├── .env.example
├── artisan
├── composer.json
├── composer.lock
└── README.md

```
