<!DOCTYPE html>
<html>

<head>
    <title>Google Calendar Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: #f0f2f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .container {
            max-width: 1400px;
            margin: 30px auto;
        }
        
        .card {
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            border: none;
        }
        
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            border-radius: 15px 15px 0 0;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 8px;
            padding: 10px 20px;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102,126,234,0.4);
        }
        
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            color: #495057;
            font-weight: 600;
        }
        
        .badge-category {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
        }
        
        .category-general { background: #6c757d; color: white; }
        .category-meeting { background: #007bff; color: white; }
        .category-personal { background: #28a745; color: white; }
        .category-work { background: #17a2b8; color: white; }
        .category-birthday { background: #fd7e14; color: white; }
        .category-holiday { background: #20c997; color: white; }
        .category-deadline { background: #dc3545; color: white; }
        
        .action-buttons .btn {
            margin: 0 3px;
            border-radius: 6px;
        }
        
        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
        .alert-success {
            border-radius: 10px;
            border-left: 4px solid #28a745;
        }
        
        .pagination {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3><i class="fas fa-calendar-alt"></i> Google Calendar Events</h3>
                        <p class="mb-0 mt-2">Manage all your calendar events</p>
                    </div>
                    <div>
                        <a href="/calendar/calendar-view" class="btn btn-light me-2">
                            <i class="fas fa-calendar-week"></i> Calendar View
                        </a>
                        <a href="/calendar/create" class="btn btn-light">
                            <i class="fas fa-plus"></i> Create Event
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Filter Section -->
                <div class="filter-section">
                    <form method="GET" action="/calendar" class="row g-3">
                        <div class="col-md-4">
                            <input type="text" name="keyword" class="form-control" 
                                   placeholder=" Search events..." value="{{ request('keyword') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="category" class="form-control">
                                <option value="">All Categories</option>
                                <option value="general" {{ request('category') == 'general' ? 'selected' : '' }}>📅 General</option>
                                <option value="meeting" {{ request('category') == 'meeting' ? 'selected' : '' }}>💼 Meeting</option>
                                <option value="personal" {{ request('category') == 'personal' ? 'selected' : '' }}>👤 Personal</option>
                                <option value="work" {{ request('category') == 'work' ? 'selected' : '' }}>🏢 Work</option>
                                <option value="birthday" {{ request('category') == 'birthday' ? 'selected' : '' }}>🎂 Birthday</option>
                                <option value="holiday" {{ request('category') == 'holiday' ? 'selected' : '' }}>🌴 Holiday</option>
                                <option value="deadline" {{ request('category') == 'deadline' ? 'selected' : '' }}>⏰ Deadline</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="start_date" class="form-control" 
                                   placeholder="From Date" value="{{ request('start_date') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                        </div>
                    </form>
                </div>
                
                <!-- Events Table -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th><i class="fas fa-tag"></i> Title</th>
                                <th><i class="fas fa-calendar-day"></i> Start</th>
                                <th><i class="fas fa-calendar-day"></i> End</th>
                                <th><i class="fas fa-category"></i> Category</th>
                                <th><i class="fas fa-cog"></i> Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($paginated as $event)
                                @php
                                    $metadata = json_decode($event->description ?? '{}', true);
                                    $category = $metadata['category'] ?? 'general';
                                @endphp
                                <tr>
                                    <td>
                                        <strong>{{ $event->name }}</strong>
                                        @if(isset($metadata['description']))
                                            <br><small class="text-muted">{{ Str::limit($metadata['description'], 50) }}</small>
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($event->startDateTime)->format('M d, Y h:i A') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($event->endDateTime)->format('M d, Y h:i A') }}</td>
                                    <td>
                                        <span class="badge-category category-{{ $category }}">
                                            @if($category == 'general')  General
                                            @elseif($category == 'meeting')  Meeting
                                            @elseif($category == 'personal')  Personal
                                            @elseif($category == 'work')  Work
                                            @elseif($category == 'birthday')  Birthday
                                            @elseif($category == 'holiday')  Holiday
                                            @elseif($category == 'deadline')  Deadline
                                            @else {{ ucfirst($category) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="action-buttons">
                                        <a href="{{ url('/calendar/show/' . $event->id) }}" 
                                           class="btn btn-sm btn-info" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ url('/calendar/edit/' . $event->id) }}" 
                                           class="btn btn-sm btn-warning" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ url('/calendar/copy/' . $event->id) }}" 
                                           class="btn btn-sm btn-secondary" title="Copy">
                                            <i class="fas fa-copy"></i>
                                        </a>
                                        <a href="{{ url('/calendar/delete/' . $event->id) }}" 
                                           class="btn btn-sm btn-danger" title="Delete"
                                           onclick="return confirm('Are you sure you want to delete this event?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                        <p class="text-muted">No events found</p>
                                        <a href="/calendar/create" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Create First Event
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $paginated->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>