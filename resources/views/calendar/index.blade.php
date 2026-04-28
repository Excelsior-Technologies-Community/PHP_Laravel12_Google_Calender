<!DOCTYPE html>
<html>
<head>
    <title>Google Calendar Events</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f4f6fb;
        }

        .card-box {
            background: #fff;
            padding: 25px;
            border-radius: 14px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        .title {
            font-size: 22px;
            font-weight: 600;
        }

        .btn-show { background: #17a2b8; color:#fff; }
        .btn-edit { background: #ffc107; color:#000; }
        .btn-delete { background: #dc3545; color:#fff; }

        .table th {
            background: #2f3542;
            color: #fff;
        }
    </style>
</head>

<body>

<div class="container mt-5">

    <div class="card-box">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center">
            <div class="title">📅 Google Calendar Events</div>

            <a href="/calendar/create" class="btn btn-success">
                + Create Event
            </a>
        </div>

        <!-- SEARCH -->
        <form method="GET" action="/calendar" class="mt-3 row g-2">
            <div class="col-md-10">
                <input type="text"
                       name="keyword"
                       class="form-control"
                       placeholder="Search event title..."
                       value="{{ request('keyword') }}">
            </div>

            <div class="col-md-2">
                <button class="btn btn-primary w-100">Search</button>
            </div>
        </form>

        <!-- TABLE -->
        <div class="table-responsive mt-4">

            <table class="table table-bordered table-hover">

                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Start</th>
                        <th>End</th>
                        <th width="220">Action</th>
                    </tr>
                </thead>

                <tbody>
                @forelse($events as $event)

                    <tr>
                        <td>{{ $event->name }}</td>
                        <td>{{ $event->startDateTime }}</td>
                        <td>{{ $event->endDateTime }}</td>

                        <td>
                            <a href="{{ url('/calendar/show/' . $event->id) }}"
                               class="btn btn-sm btn-show">Show</a>

                            <a href="{{ url('/calendar/edit/' . $event->id) }}"
                               class="btn btn-sm btn-edit">Edit</a>

                            <a href="{{ url('/calendar/delete/' . $event->id) }}"
                               class="btn btn-sm btn-delete"
                               onclick="return confirm('Are you sure?')">
                               Delete
                            </a>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            No events found
                        </td>
                    </tr>
                @endforelse
                </tbody>

            </table>
        </div>

        <!-- PAGINATION -->
        <div class="mt-3 d-flex justify-content-center">
            {{ $events->withQueryString()->links('pagination::bootstrap-5') }}
        </div>

    </div>

</div>

</body>
</html>