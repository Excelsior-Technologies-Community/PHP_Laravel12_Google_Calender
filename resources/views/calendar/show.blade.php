<!DOCTYPE html>
<html>

<head>
    <title>Event Details</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            padding: 40px 0;
        }
        
        .container {
            max-width: 800px;
            margin: auto;
        }
        
        .card {
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            border: none;
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .event-detail {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .event-label {
            font-weight: 600;
            color: #667eea;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .event-value {
            font-size: 16px;
            color: #333;
            margin-top: 5px;
        }
        
        .btn-group {
            padding: 20px;
            text-align: center;
        }
        
        .badge-category {
            padding: 8px 15px;
            border-radius: 20px;
            font-size: 14px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-calendar-check"></i> Event Details</h2>
            </div>
            
            <div class="card-body">
                <div class="event-detail">
                    <div class="event-label"><i class="fas fa-tag"></i> Event Title</div>
                    <div class="event-value">{{ $event->name }}</div>
                </div>
                
                <div class="event-detail">
                    <div class="event-label"><i class="fas fa-calendar-alt"></i> Start Date & Time</div>
                    <div class="event-value">{{ \Carbon\Carbon::parse($event->startDateTime)->format('F d, Y - h:i A') }}</div>
                </div>
                
                <div class="event-detail">
                    <div class="event-label"><i class="fas fa-calendar-alt"></i> End Date & Time</div>
                    <div class="event-value">{{ \Carbon\Carbon::parse($event->endDateTime)->format('F d, Y - h:i A') }}</div>
                </div>
                
                <div class="event-detail">
                    <div class="event-label"><i class="fas fa-category"></i> Category</div>
                    <div class="event-value">
                        <span class="badge-category category-{{ $metadata['category'] ?? 'general' }}">
                            {{ ucfirst($metadata['category'] ?? 'General') }}
                        </span>
                    </div>
                </div>
                
                @if(!empty($metadata['description']))
                <div class="event-detail">
                    <div class="event-label"><i class="fas fa-align-left"></i> Description</div>
                    <div class="event-value">{{ $metadata['description'] }}</div>
                </div>
                @endif
                
                @if(!empty($metadata['status']))
                <div class="event-detail">
                    <div class="event-label"><i class="fas fa-flag"></i> Status</div>
                    <div class="event-value">{{ ucfirst($metadata['status']) }}</div>
                </div>
                @endif
                
                <div class="btn-group">
                    <a href="/calendar/edit/{{ $event->id }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Event
                    </a>
                    <a href="/calendar" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>