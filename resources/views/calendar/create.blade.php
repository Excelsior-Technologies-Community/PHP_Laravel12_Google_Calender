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