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
                
                <!-- Waste Collection Requests -->
                <div class="col-lg-8">
                    <div class="card collections-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h3 class="mb-0">
                                <i class="fas fa-truck"></i> Waste Collection Status
                            </h3>
                            <div class="collection-stats-mini">
                                <span class="badge bg-warning">{{ $dashboardData['collection_stats']['pending'] ?? 0 }} Pending</span>
                                <span class="badge bg-info">{{ $dashboardData['collection_stats']['assigned'] ?? 0 }} Assigned</span>
                                <span class="badge bg-success">{{ $dashboardData['collection_stats']['today_collections'] ?? 0 }} Today</span>
                            </div>
                        </div>
                        
                        <div class="collections-list">
                            @forelse($dashboardData['pending_collections'] ?? [] as $collection)
                            <div class="collection-item">
                                <div class="collection-icon">
                                    <i class="fas fa-box-open"></i>
                                </div>
                                <div class="collection-details">
                                    <h5>{{ $collection->wasteReport->waste_type ?? 'Unknown' }} Collection</h5>
                                    <p class="location">
                                        <i class="fas fa-map-marker-alt"></i> 
                                        {{ $collection->wasteReport->location ?? 'Unknown Location' }}
                                    </p>
                                    <small class="text-muted">
                                        Requested {{ $collection->requested_at->diffForHumans() }}
                                    </small>
                                </div>
                                <div class="collection-status">
                                    <span class="status-badge status-{{ $collection->status }}">
                                        {{ ucfirst($collection->status) }}
                                    </span>
                                    <div class="collection-weight">
                                        {{ $collection->wasteReport->amount ?? 0 }} {{ $collection->wasteReport->unit ?? 'kg' }}
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="empty-state">
                                <i class="fas fa-check-circle text-success"></i>
                                <p>No pending waste collections!</p>
                                <small class="text-muted">All reported waste has been processed</small>
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
                            @forelse($dashboardData['recent_activity'] ?? [] as $activity)
                            <div class="activity-item">
                                <div class="activity-icon {{ $activity['needs_collection'] ? 'needs-collection' : 'processed' }}">
                                    <i class="fas {{ $activity['needs_collection'] ? 'fa-exclamation-triangle' : 'fa-check-circle' }}"></i>
                                </div>
                                <div class="activity-content">
                                    <p>
                                        <strong>{{ Str::mask($activity['user_email'], '*', 3) }}</strong> 
                                        reported <strong>{{ $activity['amount'] }}kg</strong> of 
                                        <strong>{{ $activity['type'] }}</strong> waste
                                    </p>
                                    <div class="activity-meta">
                                        <span class="location">
                                            <i class="fas fa-map-marker-alt"></i> {{ $activity['location'] }}
                                        </span>
                                        <span class="time">{{ $activity['time_ago'] }}</span>
                                        @if($activity['needs_collection'])
                                            <span class="collection-needed">
                                                <i class="fas fa-truck"></i> Collection Needed
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="empty-state">
                                <i class="fas fa-leaf text-success"></i>
                                <p>No recent activity</p>
                                <small class="text-muted">Be the first to report waste today!</small>
                            </div>
                            @endforelse
                        </div>
                        
                        <div class="activity-actions text-center mt-3">
                            <a href="{{ route('reportWaste') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Report Waste Now
                            </a>
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
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/appbar.js') }}"></script>
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
