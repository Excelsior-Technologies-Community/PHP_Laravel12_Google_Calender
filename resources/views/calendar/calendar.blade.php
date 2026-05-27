<!DOCTYPE html>
<html>

<head>
    <title>Calendar View - Google Calendar</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .container {
            max-width: 1400px;
            margin: 30px auto;
        }
        
        .card {
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
            border: none;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 15px 15px 0 0;
        }
        
        .btn-custom {
            background: white;
            color: #667eea;
            border-radius: 8px;
            padding: 8px 20px;
            margin: 0 5px;
            border: none;
            transition: all 0.3s;
        }
        
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        #calendar {
            padding: 20px;
        }
        
        .fc-event {
            cursor: pointer;
            border-radius: 5px;
            border: none;
        }
        
        .modal-content {
            border-radius: 15px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h3><i class="fas fa-calendar-alt"></i> Calendar View</h3>
                    <div>
                        <a href="/calendar" class="btn-custom">
                            <i class="fas fa-list"></i> List View
                        </a>
                        <a href="/calendar/create" class="btn-custom">
                            <i class="fas fa-plus"></i> Create Event
                        </a>
                        <a href="/calendar/export/csv" class="btn-custom">
                            <i class="fas fa-download"></i> Export CSV
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="calendar"></div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="eventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Event Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="eventDetails">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: '/calendar/events',
                editable: true,
                eventDrop: function(info) {
                    var start = info.event.start.toISOString();
                    var end = info.event.end ? info.event.end.toISOString() : start;
                    
                    fetch('/calendar/update-date/' + info.event.id, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            start: start,
                            end: end
                        })
                    }).then(response => {
                        if(!response.ok) {
                            info.revert();
                            alert("Error updating event date.");
                        }
                    }).catch(error => {
                        info.revert();
                        alert("Error updating event date.");
                    });
                },
                eventClick: function(info) {
                    var modal = new bootstrap.Modal(document.getElementById('eventModal'));
                    document.getElementById('eventDetails').innerHTML = `
                        <h4>${info.event.title}</h4>
                        <p><strong>Start:</strong> ${info.event.start.toLocaleString()}</p>
                        <p><strong>End:</strong> ${info.event.end ? info.event.end.toLocaleString() : 'N/A'}</p>
                        <p><strong>Description:</strong> ${info.event.extendedProps.description || 'No description'}</p>
                        <p><strong>Category:</strong> ${info.event.extendedProps.category || 'General'}</p>
                        <hr>
                        <a href="/calendar/show/${info.event.id}" class="btn btn-primary">View Details</a>
                        <a href="/calendar/edit/${info.event.id}" class="btn btn-warning">Edit</a>
                    `;
                    modal.show();
                },
                eventDidMount: function(info) {
                    $(info.el).tooltip({
                        title: info.event.title,
                        placement: 'top'
                    });
                }
            });
            calendar.render();
        });
    </script>
</body>

</html>