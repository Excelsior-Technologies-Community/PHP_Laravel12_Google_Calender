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