<!DOCTYPE html>
<html>

<head>
    <title>Create Event - Google Calendar</title>
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
        
        .card-header h2 {
            margin: 0;
            font-size: 28px;
        }
        
        .card-body {
            padding: 40px;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
            display: block;
        }
        
        .required:after {
            content: " *";
            color: red;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 14px;
            transition: all 0.3s;
        }
        
        input:focus, select:focus, textarea:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }
        
        .btn-submit {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 14px 30px;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            width: 100%;
            cursor: pointer;
            transition: transform 0.2s;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
        }
        
        .btn-back {
            background: #6c757d;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
        }
        
        .category-badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 12px;
            margin-right: 5px;
            cursor: pointer;
        }
        
        .category-option {
            display: inline-block;
            margin: 5px;
        }
        
        .color-preview {
            width: 30px;
            height: 30px;
            border-radius: 5px;
            display: inline-block;
            margin-left: 10px;
            vertical-align: middle;
        }
        
        .alert {
            border-radius: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-calendar-plus"></i> Create New Event</h2>
                <p class="mb-0 mt-2">Add event to Google Calendar</p>
            </div>
            
            <div class="card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                <form method="POST" action="/calendar/store" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group">
                        <label class="required">Event Title</label>
                        <input type="text" name="title" required placeholder="Enter event title">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required">Start Date & Time</label>
                                <input type="datetime-local" name="start" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="required">End Date & Time</label>
                                <input type="datetime-local" name="end" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="required">Category</label>
                        <select name="category" required>
                            <option value="general"> General</option>
                            <option value="meeting"> Meeting</option>
                            <option value="personal"> Personal</option>
                            <option value="work"> Work</option>
                            <option value="birthday"> Birthday</option>
                            <option value="holiday"> Holiday</option>
                            <option value="deadline"> Deadline</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Event Color</label>
                        <select name="color">
                            <option value="#3788d8">Blue (Default)</option>
                            <option value="#d50000">Red</option>
                            <option value="#e67c73">Pink</option>
                            <option value="#f09300">Orange</option>
                            <option value="#f6c026">Yellow</option>
                            <option value="#33b679">Green</option>
                            <option value="#0d7813">Dark Green</option>
                            <option value="#3f51b5">Indigo</option>
                            <option value="#9b59b6">Purple</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="4" placeholder="Event description..."></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Attendees (comma separated)</label>
                        <input type="text" name="attendees" placeholder="email1@example.com, email2@example.com">
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="checkbox" name="has_reminder" value="1">
                            Enable Reminders
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label>
                            <input type="radio" name="recurring" value="yes">
                            Recurring Event
                        </label>
                        <div style="margin-top: 10px; margin-left: 25px;">
                            <select name="recurrence_freq">
                                <option value="DAILY">Daily</option>
                                <option value="WEEKLY">Weekly</option>
                                <option value="MONTHLY">Monthly</option>
                            </select>
                            <input type="number" name="recurrence_count" placeholder="Number of occurrences" style="width: 200px; margin-left: 10px;">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Attachment (Optional)</label>
                        <input type="file" name="attachment">
                    </div>
                    
                    <div class="form-group">
                        <label>Event Status</label>
                        <select name="status">
                            <option value="confirmed"> Confirmed</option>
                            <option value="tentative"> Tentative</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> Create Event
                    </button>
                    
                    <div class="text-center">
                        <a href="/calendar" class="btn-back">
                            <i class="fas fa-arrow-left"></i> Back to Calendar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>