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