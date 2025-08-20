<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #2c3338; color: #ffffff; }
        .card { background-color: #1a1d20; border: none; border-radius: 10px; box-shadow: 0 0 20px rgba(0,0,0,0.3); }
        .table { color: #ffffff; }
        .table thead th { background-color: #3a4147; color: #ffffff; border-color: #4a5157; }
        .table td { border-color: #4a5157; vertical-align: middle; }
        .btn-action { padding: 5px 10px; margin: 0 2px; }
        .content-wrapper { margin-left: 250px; padding: 20px; }
        .event-image { width: 50px; height: 50px; object-fit: cover; border-radius: 5px; }
    </style>
</head>
<body>
    @include('admin.adminsidebar')
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Events Management</h2>
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEventModal">
                    <i class="fas fa-plus me-2"></i>Add New Event
                </button>
            </div>
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Location</th>
                                    <th>Description</th>
                                    <th>Created</th>
                                    <th>Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($events as $event)
                                <tr>
                                    <td>{{ $event->id }}</td>
                                    <td>
                                        @if($event->image)
                                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->name }}" class="event-image">
                                        @endif
                                    </td>
                                    <td>{{ $event->name }}</td>
                                    <td>{{ $event->date }}</td>
                                    <td>{{ $event->time }}</td>
                                    <td>{{ $event->location }}</td>
                                    <td>{{ Str::limit($event->description, 50) }}</td>
                                    <td>{{ $event->created_at }}</td>
                                    <td>{{ $event->updated_at }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-action" onclick="editEvent({{ json_encode($event) }})" data-bs-toggle="modal" data-bs-target="#editEventModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form action="{{ route('admin.events.delete', $event->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-action" onclick="return confirm('Are you sure you want to delete this event?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Event</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control bg-secondary text-white" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control bg-secondary text-white" name="image">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control bg-secondary text-white" name="date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Time</label>
                            <input type="text" class="form-control bg-secondary text-white" name="time" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control bg-secondary text-white" name="location" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control bg-secondary text-white" name="description" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Add Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Event Modal -->
    <div class="modal fade" id="editEventModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Event</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="editEventForm" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" id="edit_id">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control bg-secondary text-white" name="name" id="edit_name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" class="form-control bg-secondary text-white" name="image">
                            <small class="text-muted">Leave empty to keep current image</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Date</label>
                            <input type="date" class="form-control bg-secondary text-white" name="date" id="edit_date" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Time</label>
                            <input type="text" class="form-control bg-secondary text-white" name="time" id="edit_time" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" class="form-control bg-secondary text-white" name="location" id="edit_location" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control bg-secondary text-white" name="description" id="edit_description" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Update Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editEvent(event) {
            document.getElementById('edit_id').value = event.id;
            document.getElementById('edit_name').value = event.name;
            document.getElementById('edit_date').value = event.date;
            document.getElementById('edit_time').value = event.time;
            document.getElementById('edit_location').value = event.location;
            document.getElementById('edit_description').value = event.description;
            document.getElementById('editEventForm').action = '/admin/events/' + event.id;
        }
    </script>
</body>
</html>
