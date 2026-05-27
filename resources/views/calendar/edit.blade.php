<!DOCTYPE html>
<html>

<head>
    <title>Edit Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 40px 0;
        }
        
        .container {
            max-width: 800px;
            margin: auto;
        }
        
        .card {
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .form-group {
            margin-bottom: 25px;
            padding: 0 20px;
        }
        
        label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #333;
            display: block;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
        }
        
        .btn-update {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px;
            border: none;
            border-radius: 10px;
            width: calc(100% - 40px);
            margin: 20px;
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-edit"></i> Edit Event</h2>
            </div>
            
            <div class="card-body">
                <form method="POST" action="/calendar/update/{{ $event->id }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="form-group">
                        <label>Event Title</label>
                        <input type="text" name="title" value="{{ $event->name }}" required>
                    </div>
                    
                    <div class="row m-0">
                        <div class="col-md-6 p-0">
                            <div class="form-group">
                                <label>Start Date & Time</label>
                                <input type="datetime-local" name="start" 
                                    value="{{ \Carbon\Carbon::parse($event->startDateTime)->format('Y-m-d\TH:i') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6 p-0">
                            <div class="form-group">
                                <label>End Date & Time</label>
                                <input type="datetime-local" name="end" 
                                    value="{{ \Carbon\Carbon::parse($event->endDateTime)->format('Y-m-d\TH:i') }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Category</label>
                        <select name="category">
                            <option value="general" {{ ($metadata['category'] ?? '') == 'general' ? 'selected' : '' }}>General</option>
                            <option value="meeting" {{ ($metadata['category'] ?? '') == 'meeting' ? 'selected' : '' }}>Meeting</option>
                            <option value="personal" {{ ($metadata['category'] ?? '') == 'personal' ? 'selected' : '' }}>Personal</option>
                            <option value="work" {{ ($metadata['category'] ?? '') == 'work' ? 'selected' : '' }}>Work</option>
                            <option value="birthday" {{ ($metadata['category'] ?? '') == 'birthday' ? 'selected' : '' }}>Birthday</option>
                            <option value="holiday" {{ ($metadata['category'] ?? '') == 'holiday' ? 'selected' : '' }}>Holiday</option>
                            <option value="deadline" {{ ($metadata['category'] ?? '') == 'deadline' ? 'selected' : '' }}>Deadline</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Event Color</label>
                        <select name="color_id">
                            <option value="9" {{ ($metadata['color'] ?? '') == '9' ? 'selected' : '' }}>Blue (Default)</option>
                            <option value="11" {{ ($metadata['color'] ?? '') == '11' ? 'selected' : '' }}>Tomato (Red)</option>
                            <option value="4" {{ ($metadata['color'] ?? '') == '4' ? 'selected' : '' }}>Flamingo (Pink)</option>
                            <option value="6" {{ ($metadata['color'] ?? '') == '6' ? 'selected' : '' }}>Tangerine (Orange)</option>
                            <option value="5" {{ ($metadata['color'] ?? '') == '5' ? 'selected' : '' }}>Banana (Yellow)</option>
                            <option value="10" {{ ($metadata['color'] ?? '') == '10' ? 'selected' : '' }}>Basil (Green)</option>
                            <option value="2" {{ ($metadata['color'] ?? '') == '2' ? 'selected' : '' }}>Sage (Light Green)</option>
                            <option value="7" {{ ($metadata['color'] ?? '') == '7' ? 'selected' : '' }}>Peacock (Light Blue)</option>
                            <option value="3" {{ ($metadata['color'] ?? '') == '3' ? 'selected' : '' }}>Grape (Purple)</option>
                            <option value="8" {{ ($metadata['color'] ?? '') == '8' ? 'selected' : '' }}>Graphite (Gray)</option>
                            <option value="1" {{ ($metadata['color'] ?? '') == '1' ? 'selected' : '' }}>Lavender</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="4">{{ $metadata['description'] ?? '' }}</textarea>
                    </div>

                    <div class="form-group">
                        <label>Attendees (comma separated emails)</label>
                        <input type="text" name="attendees" value="{{ implode(',', $metadata['attendees'] ?? []) }}" placeholder="email1@example.com, email2@example.com">
                    </div>

                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 10px;">
                            <input type="checkbox" name="create_meet" value="1" style="width: auto;">
                            Generate/Regenerate Google Meet Link
                        </label>
                    </div>
                    
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 10px;">
                            <input type="checkbox" name="has_reminder" value="1" style="width: auto;">
                            Enable Default Reminders (30m & 60m)
                        </label>
                        <div style="margin-top: 10px; margin-left: 25px;">
                            <input type="number" name="reminder_minutes" placeholder="Custom reminder (minutes before)" style="width: 250px;">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label style="display: flex; align-items: center; gap: 10px;">
                            <input type="radio" name="recurring" value="yes" style="width: auto;">
                            Update to Recurring Event
                        </label>
                        <div style="margin-top: 10px; margin-left: 25px;">
                            <select name="recurrence_freq" style="width: 150px; display: inline-block;">
                                <option value="DAILY">Daily</option>
                                <option value="WEEKLY">Weekly</option>
                                <option value="MONTHLY">Monthly</option>
                            </select>
                            <input type="number" name="recurrence_count" placeholder="Number of occurrences" style="width: 200px; margin-left: 10px; display: inline-block;">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="confirmed" {{ ($metadata['status'] ?? '') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="tentative" {{ ($metadata['status'] ?? '') == 'tentative' ? 'selected' : '' }}>Tentative</option>
                            <option value="cancelled" {{ ($metadata['status'] ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="form-group p-0 text-center">
                        <button type="submit" class="btn-update">
                            <i class="fas fa-save"></i> Update Event
                        </button>
                    </div>
                    
                    <div class="text-center pb-4">
                        <a href="/calendar" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>