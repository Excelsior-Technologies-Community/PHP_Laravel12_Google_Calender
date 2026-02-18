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