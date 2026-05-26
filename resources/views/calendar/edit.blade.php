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
            width: 100%;
            margin: 20px;
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
                <form method="POST" action="/calendar/update/{{ $event->id }}">
                    @csrf
                    
                    <div class="form-group">
                        <label>Event Title</label>
                        <input type="text" name="title" value="{{ $event->name }}" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Start Date & Time</label>
                                <input type="datetime-local" name="start" 
                                    value="{{ \Carbon\Carbon::parse($event->startDateTime)->format('Y-m-d\TH:i') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
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
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>Description</label>
                        <textarea name="description" rows="4">{{ $metadata['description'] ?? '' }}</textarea>
                    </div>
                    
                    <div class="form-group">
                        <label>Status</label>
                        <select name="status">
                            <option value="confirmed" {{ ($metadata['status'] ?? '') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="tentative" {{ ($metadata['status'] ?? '') == 'tentative' ? 'selected' : '' }}>Tentative</option>
                            <option value="cancelled" {{ ($metadata['status'] ?? '') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <button type="submit" class="btn-update">
                            <i class="fas fa-save"></i> Update Event
                        </button>
                    </div>
                    
                    <div class="text-center">
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