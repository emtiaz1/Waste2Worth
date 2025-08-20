<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Waste Report Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { 
            margin-left: 250px;
            background-color: #2c3338;
            color: #ffffff;
        }
        
        .main-content {
            margin-left: 0;
            padding: 20px;
            background-color: #2c3338;
            min-height: 100vh;
            color: #ffffff;
        }
        
        .card {
            background-color: #1a1d20 !important;
            border: none !important;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            color: #ffffff;
        }
        
        .card-header {
            background-color: #343a40 !important;
            border-bottom: 1px solid #495057 !important;
            color: #ffffff !important;
        }
        
        .card-body {
            background-color: #1a1d20;
            color: #ffffff;
        }
        
        .status-badge {
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.25rem 0.5rem;
        }
        .status-pending { background-color: #ffc107; color: #000; }
        .status-assigned { background-color: #17a2b8; color: #fff; }
        .status-submitted { background-color: #6f42c1; color: #fff; }
        .status-collected { background-color: #28a745; color: #fff; }
        .status-confirmed { background-color: #007bff; color: #fff; }
        .status-rejected { background-color: #dc3545; color: #fff; }
        
        .report-card {
            background: #1a1d20 !important;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            margin-bottom: 1.5rem;
            border-left: 4px solid #007bff;
            color: #ffffff;
        }
        
        .info-section {
            background: #2c3338;
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 1rem;
            border-left: 3px solid #495057;
        }
        
        .reporter-section { border-left-color: #28a745; }
        .collector-section { border-left-color: #007bff; }
        
        .comparison-table {
            background: #1a1d20;
            border-radius: 6px;
            border: 1px solid #495057;
        }
        
        .comparison-table th {
            background: #343a40;
            font-weight: 600;
            font-size: 0.9rem;
            color: #ffffff;
            border-color: #495057;
        }
        
        .comparison-table td {
            color: #ffffff;
            border-color: #495057;
        }
        
        .admin-decision {
            background: #343a40;
            border: 1px solid #495057;
            border-radius: 6px;
            padding: 1.5rem;
            margin-top: 1rem;
        }
        
        .stats-card {
            border-radius: 8px;
            border: none;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            background-color: #1a1d20;
            color: #ffffff;
        }
        
        .form-control, .form-select {
            background-color: #495057;
            border-color: #6c757d;
            color: #ffffff;
        }
        
        .form-control:focus, .form-select:focus {
            background-color: #495057;
            border-color: #80bdff;
            color: #ffffff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }
        
        .form-control::placeholder {
            color: #adb5bd;
        }
        
        .input-group-text {
            background-color: #495057;
            border-color: #6c757d;
            color: #ffffff;
        }
        
        h1, h2, h3, h4, h5, h6 {
            color: #ffffff !important;
        }
        
        .text-muted {
            color: #adb5bd !important;
        }
        
        .text-dark {
            color: #ffffff !important;
        }
        
        .bg-light {
            background-color: #495057 !important;
        }
        
        .bg-white {
            background-color: #343a40 !important;
        }
        
        .border {
            border-color: #495057 !important;
        }
        
        .btn-group .btn {
            border-color: #495057;
        }
        
        .toast {
            background-color: #343a40;
            color: #ffffff;
        }
        
        .toast-header {
            background-color: #495057;
            color: #ffffff;
            border-bottom: 1px solid #6c757d;
        }
    </style>
</head>
<body>
    <!-- Include Admin Sidebar -->
    @include('admin.adminsidebar')
    
    <!-- Main Content -->
    <div class="main-content">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <h3 class="fw-bold text-dark mb-1">
                        <i class="fas fa-clipboard-check me-2 text-primary"></i>
                        Waste Report Management
                    </h3>
                    <p class="text-muted mb-0">Review and confirm waste collections to award eco coins</p>
                </div>
            </div>

                    <!-- Filter Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-filter"></i></span>
                                <select class="form-select" id="statusFilter">
                                    <option value="">All Statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="assigned">Assigned</option>
                                    <option value="collected">Collected (Ready for Review)</option>
                                    <option value="confirmed">Confirmed</option>
                                    <option value="rejected">Rejected</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control" id="searchInput" placeholder="Search by waste type, location, or user email">
                            </div>
                        </div>
                    </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3 mb-3">
                    <div class="card stats-card text-warning">
                        <div class="card-body text-center">
                            <i class="fas fa-hourglass-half fa-2x mb-2 text-warning"></i>
                            <h4 class="mb-1 text-white">{{ $reportsData->where('status', 'pending')->count() }}</h4>
                            <small class="text-warning">Pending</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card stats-card text-info">
                        <div class="card-body text-center">
                            <i class="fas fa-user-check fa-2x mb-2 text-info"></i>
                            <h4 class="mb-1 text-white">{{ $reportsData->where('collection.status', 'assigned')->count() }}</h4>
                            <small class="text-info">Assigned</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card stats-card text-success">
                        <div class="card-body text-center">
                            <i class="fas fa-check-circle fa-2x mb-2 text-success"></i>
                            <h4 class="mb-1 text-white">{{ $reportsData->whereIn('collection.status', ['submitted', 'collected'])->count() }}</h4>
                            <small class="text-success">Ready for Review</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-3">
                    <div class="card stats-card text-primary">
                        <div class="card-body text-center">
                            <i class="fas fa-medal fa-2x mb-2 text-primary"></i>
                            <h4 class="mb-1 text-white">{{ $reportsData->where('collection.status', 'confirmed')->count() }}</h4>
                            <small class="text-primary">Confirmed</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Waste Reports List -->
            <div class="row" id="reportsContainer">
                @forelse($reportsData as $report)
                <div class="col-12 report-item" 
                     data-status="{{ $report['collection']['status'] ?? $report['status'] }}"
                     data-search="{{ strtolower($report['waste_type'] . ' ' . $report['location'] . ' ' . $report['reporter_email'] . ' ' . ($report['collection']['collector_email'] ?? '')) }}">
                    
                    <div class="report-card">
                        <!-- Card Header -->
                        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                            <div>
                                <h5 class="mb-1 text-dark">
                                    <i class="fas fa-trash-alt text-success me-2"></i>
                                    {{ ucfirst($report['waste_type']) }} - {{ $report['amount'] }}kg
                                </h5>
                                <small class="text-muted">
                                    <i class="fas fa-map-marker-alt me-1"></i>{{ $report['location'] }}
                                    <span class="ms-3">
                                        <i class="fas fa-clock me-1"></i>{{ \Carbon\Carbon::parse($report['reported_at'])->format('M d, Y H:i') }}
                                    </span>
                                </small>
                            </div>
                            <span class="badge status-badge status-{{ $report['collection']['status'] ?? $report['status'] }}">
                                {{ ucfirst($report['collection']['status'] ?? $report['status']) }}
                            </span>
                        </div>
                        
                        <!-- Card Body -->
                        <div class="card-body">
                            <div class="row">
                                <!-- Reporter Information -->
                                <div class="col-md-6">
                                    <div class="info-section reporter-section">
                                        <h6 class="fw-bold text-success mb-3">
                                            <i class="fas fa-user me-2"></i>Reporter Details
                                        </h6>
                                        <div class="row">
                                            <div class="col-sm-4"><strong>Name:</strong></div>
                                            <div class="col-sm-8">{{ $report['reporter_name'] ?? 'Unknown User' }}</div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-sm-4"><strong>Email:</strong></div>
                                            <div class="col-sm-8">{{ $report['reporter_email'] }}</div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-sm-4"><strong>Reporting Time:</strong></div>
                                            <div class="col-sm-8">{{ \Carbon\Carbon::parse($report['reported_at'])->format('M d, Y H:i') }}</div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Collector Information -->
                                <div class="col-md-6">
                                    @if($report['collection'])
                                    <div class="info-section collector-section">
                                        <h6 class="fw-bold text-primary mb-3">
                                            <i class="fas fa-user-cog me-2"></i>Collector Details
                                        </h6>
                                        <div class="row">
                                            <div class="col-sm-4"><strong>Name:</strong></div>
                                            <div class="col-sm-8">{{ $report['collection']['collector_name'] ?? 'N/A' }}</div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-sm-4"><strong>Email:</strong></div>
                                            <div class="col-sm-8">{{ $report['collection']['collector_email'] }}</div>
                                        </div>
                                        @if($report['collection']['collected_at'])
                                        <div class="row mt-2">
                                            <div class="col-sm-4"><strong>Collection Time:</strong></div>
                                            <div class="col-sm-8">{{ \Carbon\Carbon::parse($report['collection']['collected_at'])->format('M d, Y H:i') }}</div>
                                        </div>
                                        @endif
                                    </div>
                                    @else
                                    <div class="info-section text-center">
                                        <i class="fas fa-user-slash fa-2x text-muted mb-2"></i>
                                        <p class="text-muted mb-0">No collector assigned</p>
                                    </div>
                                    @endif
                                </div>
                            </div>
                            
                            @if($report['collection'] && in_array($report['collection']['status'], ['collected', 'submitted']))
                            <!-- Weight Comparison Table -->
                            <div class="mt-4">
                                <h6 class="fw-bold mb-3">
                                    <i class="fas fa-balance-scale me-2"></i>Weight Comparison
                                </h6>
                                <div class="table-responsive">
                                    <table class="table comparison-table mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Reported Weight</th>
                                                <th class="text-center">Estimated Weight</th>
                                                <th class="text-center">Actual Weight</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center">
                                                    <span class="badge bg-info fs-6">{{ $report['amount'] }}kg</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-warning fs-6">{{ $report['collection']['estimated_weight'] ?? 'N/A' }}kg</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success fs-6">{{ $report['collection']['actual_weight'] ?? 'Not Set' }}kg</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                            @if($report['collection']['collection_notes'])
                            <div class="mt-3">
                                <h6 class="fw-bold">Collection Notes:</h6>
                                <div class="p-3 rounded border" style="background-color: #495057; border-color: #6c757d !important;">
                                    <small class="text-white">{{ $report['collection']['collection_notes'] }}</small>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Collection Photos Section -->
                            @if($report['collection']['collection_photos'] && is_array($report['collection']['collection_photos']) && count($report['collection']['collection_photos']) > 0)
                            <div class="mt-3">
                                <h6 class="fw-bold text-primary">
                                    <i class="fas fa-images me-2"></i>Collection Photos
                                </h6>
                                <div class="row">
                                    @foreach($report['collection']['collection_photos'] as $photo)
                                    <div class="col-md-4 col-sm-6 mb-3">
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $photo) }}" 
                                                 class="img-fluid rounded shadow" 
                                                 style="width: 100%; height: 200px; object-fit: cover; cursor: pointer;"
                                                 data-bs-toggle="modal" 
                                                 data-bs-target="#imageModal{{ $loop->index }}"
                                                 alt="Collection Photo">
                                            <div class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white text-center py-2 rounded-bottom">
                                                <small><i class="fas fa-search-plus me-1"></i>Click to view</small>
                                            </div>
                                        </div>
                                        
                                        <!-- Image Modal -->
                                        <div class="modal fade" id="imageModal{{ $loop->index }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title text-dark">Collection Photo {{ $loop->iteration }}</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <img src="{{ asset('storage/' . $photo) }}" class="img-fluid rounded" alt="Collection Photo">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-1"></i>
                                    <small>Review these photos to verify the collection before confirming</small>
                                </div>
                            </div>
                            @endif
                            
                            <!-- Admin Decision Section -->
                            <div class="admin-decision">
                                <h6 class="fw-bold text-dark mb-3">
                                    <i class="fas fa-user-shield me-2"></i>Admin Decision
                                </h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="weight_{{ $report['collection']['id'] }}" class="form-label">Confirmed Weight (kg):</label>
                                        <input type="number" 
                                               class="form-control" 
                                               id="weight_{{ $report['collection']['id'] }}" 
                                               step="0.1" 
                                               min="0.1" 
                                               value="{{ $report['collection']['actual_weight'] ?? $report['amount'] }}"
                                               placeholder="Enter weight">
                                    </div>
                                    <div class="col-md-5">
                                        <label for="notes_{{ $report['collection']['id'] }}" class="form-label">Admin Notes (Optional):</label>
                                        <input type="text" 
                                               class="form-control" 
                                               id="notes_{{ $report['collection']['id'] }}" 
                                               placeholder="Add any notes...">
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end">
                                        <div class="btn-group w-100">
                                            <button type="button" 
                                                    class="btn btn-outline-danger btn-reject" 
                                                    data-collection-id="{{ $report['collection']['id'] }}">
                                                <i class="fas fa-times me-1"></i>Reject
                                            </button>
                                            <button type="button" 
                                                    class="btn btn-success btn-confirm" 
                                                    data-collection-id="{{ $report['collection']['id'] }}">
                                                <i class="fas fa-check me-1"></i>Confirm
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-coins me-1"></i>
                                        Confirming will award <strong>5 coins per kg</strong> to reporter and <strong>10 coins per kg</strong> to collector
                                    </small>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <div class="card rounded p-5">
                        <div class="card-body">
                            <i class="fas fa-clipboard-list fa-3x text-muted mb-3"></i>
                            <h4 class="text-white">No waste reports found</h4>
                            <p class="text-muted">There are no waste reports to display at the moment.</p>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>
    </div>

    <!-- Success/Error Toast -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="actionToast" class="toast" role="alert">
            <div class="toast-header">
                <i class="fas fa-info-circle me-2 text-primary"></i>
                <strong class="me-auto">Admin Action</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body" id="toastMessage"></div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get CSRF token
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Filter and Search functionality
            const statusFilter = document.getElementById('statusFilter');
            const searchInput = document.getElementById('searchInput');
            const reportItems = document.querySelectorAll('.report-item');
            
            function filterReports() {
                const statusValue = statusFilter.value.toLowerCase();
                const searchValue = searchInput.value.toLowerCase();
                
                reportItems.forEach(item => {
                    const itemStatus = item.dataset.status;
                    const itemSearch = item.dataset.search;
                    
                    const statusMatch = !statusValue || itemStatus === statusValue;
                    const searchMatch = !searchValue || itemSearch.includes(searchValue);
                    
                    item.style.display = (statusMatch && searchMatch) ? 'block' : 'none';
                });
            }
            
            statusFilter.addEventListener('change', filterReports);
            searchInput.addEventListener('input', filterReports);
            
            // Confirm Collection
            document.querySelectorAll('.btn-confirm').forEach(button => {
                button.addEventListener('click', function() {
                    const collectionId = this.dataset.collectionId;
                    const weightInput = document.getElementById(`weight_${collectionId}`);
                    const notesInput = document.getElementById(`notes_${collectionId}`);
                    
                    const confirmedWeight = parseFloat(weightInput.value);
                    const adminNotes = notesInput.value.trim();
                    
                    if (!confirmedWeight || confirmedWeight <= 0) {
                        showToast('Please enter a valid weight!', 'error');
                        return;
                    }
                    
                    if (confirm(`Confirm collection with ${confirmedWeight}kg? This will award coins to both users.`)) {
                        // Disable button and show loading
                        this.disabled = true;
                        this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
                        
                        fetch('/admin/confirm-collection', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({
                                collection_id: collectionId,
                                confirmed_weight: confirmedWeight,
                                admin_notes: adminNotes
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                showToast(data.message, 'success');
                                setTimeout(() => {
                                    location.reload();
                                }, 2000);
                            } else {
                                showToast(data.error || 'Error confirming collection', 'error');
                                this.disabled = false;
                                this.innerHTML = '<i class="fas fa-check me-2"></i>Confirm & Award Coins';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            showToast('Network error. Please try again.', 'error');
                            this.disabled = false;
                            this.innerHTML = '<i class="fas fa-check me-2"></i>Confirm & Award Coins';
                        });
                    }
                });
            });
            
            // Reject Collection
            document.querySelectorAll('.btn-reject').forEach(button => {
                button.addEventListener('click', function() {
                    const collectionId = this.dataset.collectionId;
                    const reason = prompt('Please enter rejection reason:');
                    
                    if (!reason || reason.trim() === '') {
                        return;
                    }
                    
                    // Disable button and show loading
                    this.disabled = true;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
                    
                    fetch('/admin/reject-collection', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token
                        },
                        body: JSON.stringify({
                            collection_id: collectionId,
                            rejection_reason: reason.trim()
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            showToast(data.message, 'success');
                            setTimeout(() => {
                                location.reload();
                            }, 2000);
                        } else {
                            showToast(data.error || 'Error rejecting collection', 'error');
                            this.disabled = false;
                            this.innerHTML = '<i class="fas fa-times me-2"></i>Reject Collection';
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showToast('Network error. Please try again.', 'error');
                        this.disabled = false;
                        this.innerHTML = '<i class="fas fa-times me-2"></i>Reject Collection';
                    });
                });
            });
            
            // Show Toast function
            function showToast(message, type = 'info') {
                const toast = document.getElementById('actionToast');
                const toastMessage = document.getElementById('toastMessage');
                
                // Update message and style
                toastMessage.textContent = message;
                toast.className = `toast ${type === 'success' ? 'text-bg-success' : type === 'error' ? 'text-bg-danger' : 'text-bg-info'}`;
                
                // Show toast
                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
            }
        });
    </script>
</body>
</html>
