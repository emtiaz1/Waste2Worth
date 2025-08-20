<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Waste2Worth - Dashboard</title>
    <link rel="shortcut icon" href="/frontend/logo.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/appbar.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    @include('layouts.appbar')
    <div class="layout" id="mainLayout">
        <main class="main-content">
            @if($profile)
            
            <!-- Dashboard Header with User Overview -->
            <section class="dashboard-header">
                <div class="row g-4">
                    <!-- User Profile Overview -->
                    <div class="col-lg-8">
                        <div class="card user-overview-card">
                            <div class="user-overview">
                                <div class="user-avatar">
                                    @if($profile->profile_picture)
                                        <img src="{{ asset('storage/' . $profile->profile_picture) }}?t={{ time() }}" 
                                             alt="Profile Picture" class="avatar-img">
                                    @else
                                        <img src="{{ asset('frontend/image/dp.jpg') }}" 
                                             alt="Default Profile" class="avatar-img">
                                    @endif
                                    <div class="status-indicator active"></div>
                                </div>
                                <div class="user-info">
                                    <h2>Welcome back, {{ $profile->username ?? 'User' }}!</h2>
                                    <p class="user-status">{{ $profile->status ?? 'Active Environmental Contributor' }}</p>
                                    <div class="user-badges">
                                        @if(($profile->personal_stats['total_reports'] ?? 0) >= 10)
                                            <span class="badge bg-success"><i class="fas fa-award"></i> Eco Warrior</span>
                                        @endif
                                        @if(($profile->community_rank['percentile'] ?? 0) >= 80)
                                            <span class="badge bg-warning"><i class="fas fa-crown"></i> Top Contributor</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="user-actions">
                                    <a href="{{ route('reportWaste') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Report Waste
                                    </a>
                                    <a href="{{ route('profile.show') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-user-edit"></i> Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Stats Cards -->
                    <div class="col-lg-4">
                        <div class="quick-stats">
                            <div class="stat-card eco-coins">
                                <div class="stat-icon">
                                    <i class="fas fa-coins"></i>
                                </div>
                                <div class="stat-content">
                                    <h3>{{ $profile->personal_stats['coins_available'] ?? 0 }}</h3>
                                    <p>Available Eco Coins</p>
                                    <small class="text-success">+{{ $profile->personal_stats['monthly_coins'] ?? 0 }} this month</small>
                                </div>
                            </div>
                            
                            <div class="stat-card waste-reports">
                                <div class="stat-icon">
                                    <i class="fas fa-recycle"></i>
                                </div>
                                <div class="stat-content">
                                    <h3>{{ $profile->personal_stats['total_reports'] ?? 0 }}</h3>
                                    <p>Reports Submitted</p>
                                    <small class="text-info">{{ $profile->personal_stats['weekly_reports'] ?? 0 }} this week</small>
                                </div>
                            </div>
                            
                            <div class="stat-card community-rank">
                                <div class="stat-icon">
                                    <i class="fas fa-trophy"></i>
                                </div>
                                <div class="stat-content">
                                    <h3>#{{ $profile->community_rank['rank'] ?? 'N/A' }}</h3>
                                    <p>Community Rank</p>
                                    <small class="text-warning">Top {{ $profile->community_rank['percentile'] ?? 0 }}%</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Main Dashboard Content -->
            <div class="row g-4 mt-2">
                
                <!-- Personal Impact Summary -->
                <div class="col-lg-4">
                    <div class="card impact-summary-card">
                        <h3 class="card-title">
                            <i class="fas fa-leaf"></i> Your Environmental Impact
                        </h3>
                        
                        <div class="impact-metrics">
                            <div class="metric">
                                <div class="metric-value">{{ $profile->personal_stats['total_waste_reported'] ?? '0' }} kg</div>
                                <div class="metric-label">Total Waste Reported</div>
                            </div>
                            
                            <div class="metric">
                                <div class="metric-value">{{ $profile->waste_impact['carbon_footprint_saved'] ?? '0' }} kg</div>
                                <div class="metric-label">CO₂ Equivalent Saved</div>
                            </div>
                            
                            <div class="metric">
                                <div class="metric-value">{{ $profile->waste_impact['most_reported_type'] ?? 'None' }}</div>
                                <div class="metric-label">Most Reported Type</div>
                            </div>
                        </div>
                        
                        <div class="environmental-score">
                            <h5>Environmental Score</h5>
                            <div class="progress mb-2">
                                <div class="progress-bar bg-success" role="progressbar" 
                                     style="width: {{ $profile->waste_impact['environmental_score'] ?? 0 }}%"></div>
                            </div>
                            <small>{{ $profile->waste_impact['environmental_score'] ?? 0 }}/100 - Keep up the great work!</small>
                        </div>
                        
                        @if(($profile->waste_impact['recent_reports_30_days'] ?? 0) > 0)
                        <div class="recent-activity mt-3">
                            <p class="text-success">
                                <i class="fas fa-calendar-check"></i>
                                {{ $profile->waste_impact['recent_reports_30_days'] }} reports in last 30 days
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
                
                <!-- Available Waste Collections (Other Users' Reports) -->
                <div class="col-lg-8">
                    <div class="card collections-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">
                                <i class="fas fa-recycle"></i> Available for Collection
                            </h3>
                            <div class="collection-stats-mini">
                                <span class="badge bg-primary">{{ count($dashboardData['available_collections'] ?? []) }} Available</span>
                                <span class="badge bg-success">Earn Eco Coins</span>
                            </div>
                        </div>
                        
                        <div class="collections-list">
                            @forelse($dashboardData['available_collections'] ?? [] as $collection)
                            <div class="collection-item" data-waste-id="{{ $collection['id'] }}">
                                <div class="collection-icon priority-{{ strtolower($collection['priority']) }}">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <div class="collection-details">
                                    <h5>{{ $collection['type'] }} Waste</h5>
                                    <p class="location">
                                        <i class="fas fa-map-marker-alt"></i> 
                                        {{ $collection['location'] }}
                                    </p>
                                    <p class="reporter">
                                        <i class="fas fa-user"></i> 
                                        Reported by {{ $collection['reported_by'] }}
                                    </p>
                                    <small class="text-muted">
                                        {{ $collection['time_ago'] }}
                                    </small>
                                </div>
                                <div class="collection-actions">
                                    <div class="collection-priority">
                                        <span class="priority-badge priority-{{ strtolower($collection['priority']) }}">
                                            {{ $collection['priority'] }} Priority
                                        </span>
                                    </div>
                                    <div class="collection-weight">
                                        {{ $collection['amount'] }} kg
                                    </div>
                                    <button class="btn btn-sm btn-success collect-btn" data-waste-id="{{ $collection['id'] }}">
                                        <i class="fas fa-plus"></i> Collect
                                    </button>
                                </div>
                            </div>
                            @empty
                            <div class="empty-state">
                                <i class="fas fa-leaf text-success"></i>
                                <p>No waste available for collection!</p>
                                <small class="text-muted">Check back later for new reports</small>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- My Collection Assignments and My Waste Reports -->
            <div class="row g-4 dashboard-section">
                <!-- My Collection Assignments -->
                <div class="col-lg-6">
                    <div class="card my-collections-card">
                        <div class="card-header">
                            <h3 class="mb-0">
                                <i class="fas fa-clipboard-list"></i> My Collection Tasks
                            </h3>
                        </div>
                        <div class="card-body">
                            @forelse($dashboardData['my_collection_requests'] ?? [] as $request)
                            <div class="my-collection-item">
                                <div class="collection-info">
                                    <h6>{{ $request['waste_type'] }} Collection</h6>
                                    <p class="mb-1"><i class="fas fa-map-marker-alt"></i> {{ $request['location'] }}</p>
                                    <small class="text-muted">Assigned on {{ $request['assigned_date'] }}</small>
                                </div>
                                <div class="collection-meta">
                                    <span class="status-badge status-{{ $request['status'] }}">
                                        {{ ucfirst($request['status']) }}
                                    </span>
                                    <div class="weight-info">{{ $request['expected_weight'] }} kg</div>
                                    @if($request['status'] === 'assigned')
                                    <button class="btn btn-sm btn-primary submit-collection-btn" 
                                            data-collection-id="{{ $request['collection_id'] }}"
                                            data-waste-type="{{ $request['waste_type'] }}"
                                            data-location="{{ $request['location'] }}"
                                            data-expected-weight="{{ $request['expected_weight'] }}">
                                        <i class="fas fa-check"></i> Submit Collection
                                    </button>
                                    @elseif($request['status'] === 'completed')
                                    <small class="text-success"><i class="fas fa-check-circle"></i> Completed</small>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="empty-state-small">
                                <i class="fas fa-clipboard-check text-muted"></i>
                                <p>No active collection assignments</p>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <!-- My Waste Reports -->
                <div class="col-lg-6">
                    <div class="card my-reports-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">
                                <i class="fas fa-file-alt"></i> My Waste Reports
                            </h3>
                            <a href="/wastereport" class="btn btn-sm btn-outline-success">
                                <i class="fas fa-plus"></i> New Report
                            </a>
                        </div>
                        <div class="card-body">
                            @forelse($dashboardData['my_waste_reports'] ?? [] as $report)
                            <div class="my-report-item">
                                <div class="report-info">
                                    <h6>{{ $report['type'] }}</h6>
                                    <p class="mb-1"><i class="fas fa-map-marker-alt"></i> {{ $report['location'] }}</p>
                                    <small class="text-muted">{{ $report['time_ago'] }}</small>
                                </div>
                                <div class="report-meta">
                                    <span class="status-badge status-{{ $report['status'] }}">
                                        @if($report['is_collected'])
                                            <i class="fas fa-check"></i> Collected
                                        @else
                                            <i class="fas fa-clock"></i> {{ ucfirst($report['status']) }}
                                        @endif
                                    </span>
                                    <div class="weight-info">{{ $report['amount'] }} kg</div>
                                </div>
                            </div>
                            @empty
                            <div class="empty-state-small">
                                <i class="fas fa-plus-circle text-muted"></i>
                                <p>No waste reports yet</p>
                                <a href="/wastereport" class="btn btn-sm btn-success mt-2">Report Waste</a>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Community Activity & Events -->
            <div class="row g-4 mt-2">
                
                <!-- Community Statistics -->
                <div class="col-lg-6">
                    <div class="card community-stats-card">
                        <h3 class="card-title">
                            <i class="fas fa-users"></i> Community Overview
                        </h3>
                        
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number">{{ $dashboardData['community_stats']['total_reports'] ?? 0 }}</div>
                                <div class="stat-desc">Total Reports</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">{{ $dashboardData['community_stats']['total_waste_reported'] ?? '0' }}kg</div>
                                <div class="stat-desc">Waste Reported</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">{{ $dashboardData['community_stats']['active_users'] ?? 0 }}</div>
                                <div class="stat-desc">Active Users</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">{{ $dashboardData['community_stats']['today_reports'] ?? 0 }}</div>
                                <div class="stat-desc">Today's Reports</div>
                            </div>
                        </div>
                        
                        <div class="community-progress mt-4">
                            <h5>Weekly Progress</h5>
                            <div class="progress mb-2">
                                <div class="progress-bar bg-primary" role="progressbar" 
                                     style="width: {{ min(($dashboardData['community_stats']['this_week_reports'] ?? 0) / 50 * 100, 100) }}%"></div>
                            </div>
                            <small>{{ $dashboardData['community_stats']['this_week_reports'] ?? 0 }}/50 weekly goal</small>
                        </div>
                    </div>
                </div>
                
                <!-- Ongoing Events -->
                <div class="col-lg-6">
                    <div class="card events-card">
                        <h3 class="card-title">
                            <i class="fas fa-calendar-alt"></i> Upcoming Events
                        </h3>
                        
                        <div class="events-list">
                            @forelse($dashboardData['ongoing_events'] ?? [] as $event)
                            <div class="event-item">
                                <div class="event-date">
                                    <div class="date-day">{{ date('d', strtotime($event['date'])) }}</div>
                                    <div class="date-month">{{ date('M', strtotime($event['date'])) }}</div>
                                </div>
                                <div class="event-details">
                                    <h5>{{ $event['name'] }}</h5>
                                    <p><i class="fas fa-map-marker-alt"></i> {{ $event['location'] }}</p>
                                    <small class="text-muted">{{ $event['participants'] }} participants registered</small>
                                </div>
                                <div class="event-actions">
                                    <a href="{{ route('event') }}" class="btn btn-outline-primary btn-sm">
                                        View Details
                                    </a>
                                </div>
                            </div>
                            @empty
                            <div class="empty-state">
                                <i class="fas fa-calendar-plus text-primary"></i>
                                <p>No upcoming events</p>
                                <small class="text-muted">Check back soon for new community events!</small>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Recent Activity & Global Impact -->
            <div class="row g-4 mt-2">
                
                <!-- Recent Community Activity -->
                <div class="col-lg-8">
                    <div class="card activity-feed-card">
                        <h3 class="card-title">
                            <i class="fas fa-activity"></i> Recent Community Activity
                        </h3>
                        
                        <div class="activity-feed" id="activityFeed">
                            @forelse($dashboardData['recent_community_activity'] ?? [] as $activity)
                            <div class="activity-item">
                                <div class="activity-icon {{ $activity['can_collect'] ? 'needs-collection' : 'processed' }}">
                                    <i class="fas {{ $activity['can_collect'] ? 'fa-exclamation-triangle' : 'fa-check-circle' }}"></i>
                                </div>
                                <div class="activity-content">
                                    <p>
                                        <strong>{{ $activity['reporter_email'] ? Str::mask($activity['reporter_email'], '*', 3) : 'Anonymous' }}</strong> 
                                        reported <strong>{{ $activity['amount'] }}kg</strong> of 
                                        <strong>{{ $activity['type'] }}</strong> waste
                                    </p>
                                    <div class="activity-meta">
                                        <span class="location">
                                            <i class="fas fa-map-marker-alt"></i> {{ $activity['location'] }}
                                        </span>
                                        <span class="time">{{ $activity['time_ago'] }}</span>
                                        @if($activity['can_collect'])
                                            <span class="collection-available">
                                                <i class="fas fa-recycle text-success"></i> Available for Collection
                                            </span>
                                        @else
                                            <span class="collection-assigned">
                                                <i class="fas fa-user-check text-warning"></i> Already Assigned
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                @if($activity['can_collect'])
                                <div class="activity-action">
                                    <button class="btn btn-sm btn-success collect-btn" data-waste-id="{{ $activity['id'] }}">
                                        <i class="fas fa-plus"></i> Collect
                                    </button>
                                </div>
                                @else
                                <div class="activity-action">
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock"></i> Pending Collection
                                    </span>
                                </div>
                                @endif
                            </div>
                            @empty
                            <div class="empty-state">
                                <i class="fas fa-leaf text-success"></i>
                                <p>No recent community activity</p>
                                <small class="text-muted">Community members' waste reports will appear here</small>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                
                <!-- Global Impact -->
                <div class="col-lg-4">
                    <div class="card global-impact-card">
                        <h3 class="card-title">
                            <i class="fas fa-globe"></i> Global Impact
                        </h3>
                        
                        <div class="impact-metrics">
                            <div class="metric global">
                                <div class="metric-icon"><i class="fas fa-weight"></i></div>
                                <div class="metric-details">
                                    <div class="metric-value">{{ $dashboardData['global_impact']['total_waste_reported'] ?? '0' }}kg</div>
                                    <div class="metric-label">Total Waste Reported</div>
                                </div>
                            </div>
                            
                            <div class="metric global">
                                <div class="metric-icon"><i class="fas fa-truck-pickup"></i></div>
                                <div class="metric-details">
                                    <div class="metric-value">{{ $dashboardData['global_impact']['total_collected'] ?? '0' }}kg</div>
                                    <div class="metric-label">Successfully Collected</div>
                                </div>
                            </div>
                            
                            <div class="metric global">
                                <div class="metric-icon"><i class="fas fa-cloud"></i></div>
                                <div class="metric-details">
                                    <div class="metric-value">{{ $dashboardData['global_impact']['carbon_saved'] ?? '0' }}kg</div>
                                    <div class="metric-label">CO₂ Equivalent Saved</div>
                                </div>
                            </div>
                            
                            <div class="metric global">
                                <div class="metric-icon"><i class="fas fa-tree"></i></div>
                                <div class="metric-details">
                                    <div class="metric-value">{{ $dashboardData['global_impact']['trees_equivalent'] ?? '0' }}</div>
                                    <div class="metric-label">Trees Equivalent</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="impact-message mt-4">
                            <div class="alert alert-success">
                                <i class="fas fa-heart"></i>
                                Together, we're making a difference! Every report counts towards a cleaner environment.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            @else
            <!-- Guest User View -->
            <div class="guest-welcome">
                <div class="card text-center">
                    <div class="card-body">
                        <i class="fas fa-leaf display-1 text-success mb-3"></i>
                        <h1>Welcome to Waste2Worth</h1>
                        <p class="lead">Join our community of environmental champions and start making a difference today!</p>
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg">
                            <i class="fas fa-sign-in-alt"></i> Sign In to Get Started
                        </a>
                    </div>
                </div>
            </div>
            @endif
            
        </main>
    </div>

    <!-- Collection Submission Modal -->
    <div class="modal fade" id="submitCollectionModal" tabindex="-1" aria-labelledby="submitCollectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="submitCollectionModalLabel">
                        <i class="fas fa-check-circle"></i> Submit Collection
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="submitCollectionForm" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="collection-details-summary mb-4">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6>Collection Details</h6>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Waste Type:</strong> <span id="modal-waste-type"></span></p>
                                            <p><strong>Location:</strong> <span id="modal-location"></span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Expected Weight:</strong> <span id="modal-expected-weight"></span> kg</p>
                                            <p><strong>Status:</strong> <span class="badge bg-warning">Assigned</span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="actual_weight" class="form-label">
                                <i class="fas fa-weight"></i> Actual Weight Collected (kg) *
                            </label>
                            <input type="number" class="form-control" id="actual_weight" name="actual_weight" 
                                   step="0.1" min="0" required>
                            <div class="form-text">Enter the actual weight of waste collected</div>
                        </div>

                        <div class="mb-3">
                            <label for="collection_photos" class="form-label">
                                <i class="fas fa-camera"></i> Collection Photos
                            </label>
                            <input type="file" class="form-control" id="collection_photos" name="collection_photos[]" 
                                   accept="image/*" multiple>
                            <div class="form-text">Upload photos of the collected waste (optional)</div>
                        </div>

                        <div class="mb-3">
                            <label for="collection_notes" class="form-label">
                                <i class="fas fa-sticky-note"></i> Collection Notes
                            </label>
                            <textarea class="form-control" id="collection_notes" name="collection_notes" 
                                      rows="3" placeholder="Any additional notes about the collection..."></textarea>
                        </div>

                        <input type="hidden" id="collection_id" name="collection_id">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Submit Collection
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/appbar.js') }}"></script>
    
    <script>
        // Handle waste collection requests
        document.addEventListener('DOMContentLoaded', function() {
            // Collection button functionality
            document.querySelectorAll('.collect-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const wasteId = this.getAttribute('data-waste-id');
                    const btn = this;
                    
                    // Disable button during request
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                    
                    // Send AJAX request
                    fetch('/request-collection', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            waste_report_id: wasteId
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update button to show success
                            btn.innerHTML = '<i class="fas fa-check"></i> Requested';
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-secondary');
                            
                            // Show success message
                            showNotification('Collection request submitted successfully!', 'success');
                            
                            // Optionally hide the item or refresh section
                            setTimeout(() => {
                                const item = btn.closest('.collection-item, .activity-item');
                                if (item) {
                                    item.style.transition = 'opacity 0.5s';
                                    item.style.opacity = '0.5';
                                }
                            }, 1000);
                        } else {
                            throw new Error(data.error || 'Something went wrong');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showNotification(error.message || 'Failed to submit collection request', 'error');
                        
                        // Reset button
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-plus"></i> Collect';
                    });
                });
            });
            
            // Notification system
            function showNotification(message, type) {
                const notification = document.createElement('div');
                notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} notification-toast`;
                notification.innerHTML = `
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                
                // Add to page
                document.body.appendChild(notification);
                
                // Auto-remove after 5 seconds
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 5000);
            }

            // Submit Collection functionality
            document.querySelectorAll('.submit-collection-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const collectionId = this.getAttribute('data-collection-id');
                    const wasteType = this.getAttribute('data-waste-type');
                    const location = this.getAttribute('data-location');
                    const expectedWeight = this.getAttribute('data-expected-weight');
                    
                    // Populate modal with collection details
                    document.getElementById('modal-waste-type').textContent = wasteType;
                    document.getElementById('modal-location').textContent = location;
                    document.getElementById('modal-expected-weight').textContent = expectedWeight;
                    document.getElementById('collection_id').value = collectionId;
                    
                    // Show modal
                    const modal = new bootstrap.Modal(document.getElementById('submitCollectionModal'));
                    modal.show();
                });
            });

            // Handle collection submission form
            document.getElementById('submitCollectionForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(this);
                const submitBtn = this.querySelector('button[type="submit"]');
                
                // Disable submit button
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
                
                fetch('/submit-collection', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('submitCollectionModal'));
                        modal.hide();
                        
                        // Show success message
                        showNotification('Collection submitted successfully!', 'success');
                        
                        // Refresh the page after a short delay to show updated status
                        setTimeout(() => {
                            window.location.reload();
                        }, 2000);
                    } else {
                        throw new Error(data.error || 'Something went wrong');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification(error.message || 'Failed to submit collection', 'error');
                })
                .finally(() => {
                    // Re-enable submit button
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-check"></i> Submit Collection';
                });
            });
        });
    </script>
    
    <style>
        .notification-toast {
            position: fixed;
            top: 80px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .priority-high { background-color: #dc3545; color: white; }
        .priority-medium { background-color: #ffc107; color: black; }
        .priority-low { background-color: #28a745; color: white; }
        
        .priority-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .collection-item {
            background: white;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            margin-bottom: 1rem;
            padding: 1rem;
            transition: all 0.3s ease;
        }
        
        .collection-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transform: translateY(-2px);
        }
        
        .my-collection-item, .my-report-item {
            background: #f8f9fa;
            border-left: 4px solid #43a047;
            padding: 1rem;
            margin-bottom: 0.75rem;
            border-radius: 8px;
        }
        
        .status-badge {
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 500;
        }
        
        .status-pending { background-color: #fff3cd; color: #856404; }
        .status-assigned { background-color: #cff4fc; color: #055160; }
        .status-collected { background-color: #d1e7dd; color: #0a3622; }
        .status-completed { background-color: #d1e7dd; color: #0a3622; }
        
        .collection-available {
            color: #28a745;
            font-weight: 500;
        }
        
        .collection-assigned {
            color: #ffc107;
            font-weight: 500;
        }
        
        .empty-state-small {
            text-align: center;
            padding: 2rem 1rem;
            color: #6c757d;
        }
        
        .activity-action {
            margin-left: auto;
            display: flex;
            align-items: center;
        }
        
        .collection-actions {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
        }

        .submit-collection-btn {
            font-size: 0.875rem;
            padding: 0.375rem 0.75rem;
        }

        .my-collection-item {
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .collection-meta {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 0.5rem;
        }

        .collection-details-summary .card {
            border: 0;
        }
    </style>
    <script>
        // Real-time dashboard updates
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-refresh activity feed every 5 minutes
            setInterval(function() {
                if (document.hasFocus()) {
                    refreshActivityFeed();
                }
            }, 300000); // 5 minutes
            
            // Animate progress bars
            animateProgressBars();
            
            // Animate counters
            animateCounters();
        });
        
        function refreshActivityFeed() {
            // This would make an AJAX call to refresh the activity feed
            console.log('Refreshing activity feed...');
        }
        
        function animateProgressBars() {
            document.querySelectorAll('.progress-bar').forEach(bar => {
                const width = bar.style.width;
                bar.style.width = '0%';
                bar.style.transition = 'width 1.5s ease-in-out';
                
                setTimeout(() => {
                    bar.style.width = width;
                }, 200);
            });
        }
        
        function animateCounters() {
            document.querySelectorAll('.stat-number').forEach(counter => {
                const target = parseInt(counter.textContent) || 0;
                let current = 0;
                const increment = target / 50;
                const timer = setInterval(() => {
                    current += increment;
                    if (current >= target) {
                        current = target;
                        clearInterval(timer);
                    }
                    counter.textContent = Math.floor(current);
                }, 30);
            });
        }
    </script>
</body>

</html>
