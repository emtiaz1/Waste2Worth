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
        
        /* Enhanced Activity Feed Styles - Simplified */
        .activity-feed-simple {
            max-height: 500px;
            overflow-y: auto;
        }
        
        .activity-item-simple {
            display: flex;
            align-items: flex-start;
            padding: 1rem;
            border-bottom: 1px solid #e9ecef;
            transition: background-color 0.2s ease;
        }
        
        .activity-item-simple:hover {
            background-color: #f8f9fa;
        }
        
        .activity-detailed-info {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 0.5rem;
            border-left: 3px solid #007bff;
        }
        
        .status-detail-item {
            margin-bottom: 0.25rem;
        }
        
        .status-detail-item:last-child {
            margin-bottom: 0;
        }
            background-color: #f8f9fa;
        }
        
        .activity-item-simple:last-child {
            border-bottom: none;
        }
        
        .activity-status-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            margin-right: 1rem;
            flex-shrink: 0;
        }
        
        .activity-status-icon.status-available { background-color: #0d6efd; }
        .activity-status-icon.status-assigned { background-color: #fd7e14; }
        .activity-status-icon.status-submitted { background-color: #0dcaf0; }
        .activity-status-icon.status-collected { background-color: #198754; }
        .activity-status-icon.status-cancelled { background-color: #dc3545; }
        
        .activity-content-main {
            flex: 1;
            min-width: 0;
        }
        
        .activity-header-simple {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }
        
        .status-badge {
            padding: 0.25rem 0.6rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-badge.badge-primary { background-color: #0d6efd; color: white; }
        .status-badge.badge-warning { background-color: #ffc107; color: #212529; }
        .status-badge.badge-info { background-color: #0dcaf0; color: #212529; }
        .status-badge.badge-success { background-color: #198754; color: white; }
        .status-badge.badge-danger { background-color: #dc3545; color: white; }
        
        .activity-time-simple {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .activity-message-main {
            margin-bottom: 0.75rem;
            color: #495057;
            line-height: 1.4;
            font-size: 0.95rem;
        }
        
        .activity-details-simple {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .activity-details-simple span {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        
        .activity-details-simple i {
            width: 14px;
            color: #9c9c9c;
        }
        
        .activity-action-simple {
            margin-left: 1rem;
            flex-shrink: 0;
        }
        
        .activity-summary-simple {
            border-top: 2px solid #e9ecef;
        }
        
        .summary-stat-simple {
            text-align: center;
            padding: 0.5rem;
        }
        
        .stat-number-simple {
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .stat-label-simple {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: 500;
        }
        
        .empty-state-simple {
            text-align: center;
            padding: 3rem 1rem;
            color: #6c757d;
        }
        
        .empty-state-simple i {
            font-size: 3rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        
        .empty-state-simple h5 {
            margin-bottom: 0.5rem;
            color: #495057;
        }
        
        /* Enhanced Stat Cards in Flex Layout */
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
            border: 1px solid #e9ecef;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            border-color: #198754;
        }
        
        .stat-card.eco-coins:hover {
            border-color: #ffc107;
        }
        
        .stat-card.waste-reports:hover {
            border-color: #0d6efd;
        }
        
        .stat-card.community-rank:hover {
            border-color: #fd7e14;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }
        
        .stat-card.eco-coins .stat-icon {
            background: linear-gradient(135deg, #ffc107, #ffca28);
            color: white;
        }
        
        .stat-card.waste-reports .stat-icon {
            background: linear-gradient(135deg, #0d6efd, #0b5ed7);
            color: white;
        }
        
        .stat-card.community-rank .stat-icon {
            background: linear-gradient(135deg, #fd7e14, #e55a0e);
            color: white;
        }
        
        .stat-content {
            flex: 1;
            min-width: 0;
        }
        
        .stat-content h3 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 0.25rem 0;
            color: #212529;
        }
        
        .stat-content p {
            margin: 0 0 0.5rem 0;
            font-size: 0.95rem;
            font-weight: 600;
            color: #6c757d;
        }
        
        .stat-content small {
            font-size: 0.8rem;
            font-weight: 500;
        }
        
        /* Mobile responsiveness for stat cards */
        @media (max-width: 768px) {
            .stat-card {
                padding: 1rem;
                gap: 0.75rem;
            }
            
            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }
            
            .stat-content h3 {
                font-size: 1.5rem;
            }
        }
        
        /* Mobile responsiveness */
        @media (max-width: 768px) {
            .activity-item-simple {
                flex-direction: column;
                align-items: stretch;
            }
            
            .activity-status-icon {
                align-self: flex-start;
                margin-bottom: 1rem;
                margin-right: 0;
            }
            
            .activity-action-simple {
                margin-left: 0;
                margin-top: 1rem;
            }
            
            .activity-details-simple {
                flex-direction: column;
                gap: 0.5rem;
            }
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
        .status-submitted { background-color: #e2e3e5; color: #383d41; }
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
</head>

<body>
    @include('layouts.appbar')
    <div class="layout" id="mainLayout">
        <main class="main-content">
            @if($profileData)
            
            <!-- Dashboard Header with User Overview -->
            <section class="dashboard-header">
                <div class="row g-4">
                    <!-- User Profile Overview -->
                    <div class="col-12">
                        <div class="card user-overview-card">
                            <div class="user-overview">
                                <div class="user-avatar">
                                    @if($profileData->profile_picture)
                                        <img src="{{ asset('storage/' . $profileData->profile_picture) }}?t={{ time() }}" 
                                             alt="Profile Picture" class="avatar-img">
                                    @else
                                        <img src="{{ asset('frontend/image/dp.jpg') }}" 
                                             alt="Default Profile" class="avatar-img">
                                    @endif
                                    <div class="status-indicator active"></div>
                                </div>
                                <div class="user-info">
                                    <h2>Welcome back, {{ $profileData->username ?? 'User' }}!</h2>
                                    <p class="user-status">{{ $profileData->status ?? 'Active Environmental Contributor' }}</p>
                                    <div class="user-badges">
                                        @if(($profileData->personal_stats['total_reports'] ?? 0) >= 10)
                                            <span class="badge bg-success"><i class="fas fa-award"></i> Eco Warrior</span>
                                        @endif
                                        @if(($profileData->community_rank['percentile'] ?? 0) >= 80)
                                            <span class="badge bg-warning"><i class="fas fa-crown"></i> Top Contributor</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="user-actions">
                                    <a href="{{ route('reportWaste') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Report Waste
                                    </a>
                                    <a href="{{ route('profile.show') }}" class="btn btn-light">
                                        <i class="fas fa-user-edit"></i> Profile
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Stats Cards - Now in Flex Row Below Welcome Card -->
                    <div class="col-12">
                        <div class="row g-3">
                            <div class="col-lg-4 col-md-6">
                                <div class="stat-card eco-coins">
                                    <div class="stat-icon">
                                        <i class="fas fa-coins"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3>{{ $profileData->personal_stats['coins_available'] ?? 0 }}</h3>
                                        <p>Available Eco Coins</p>
                                        <div class="coin-details">
                                            <small class="text-success">+{{ $profileData->personal_stats['monthly_coins'] ?? 0 }} this month</small>
                                            @if(($profileData->personal_stats['total_eco_coins'] ?? 0) > 0)
                                                <br><small class="text-muted">{{ $profileData->personal_stats['total_eco_coins'] ?? 0 }} total earned</small>
                                            @endif
                                            @if(($profileData->personal_stats['coins_spent'] ?? 0) > 0)
                                                <br><small class="text-warning">{{ $profileData->personal_stats['coins_spent'] ?? 0 }} spent on purchases</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-6">
                                <div class="stat-card waste-reports">
                                    <div class="stat-icon">
                                        <i class="fas fa-recycle"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3>{{ $profileData->personal_stats['total_reports'] ?? 0 }}</h3>
                                        <p>Reports Submitted</p>
                                        <small class="text-info">{{ $profileData->personal_stats['weekly_reports'] ?? 0 }} this week</small>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4 col-md-12">
                                <div class="stat-card community-rank">
                                    <div class="stat-icon">
                                        <i class="fas fa-trophy"></i>
                                    </div>
                                    <div class="stat-content">
                                        <h3>#{{ $profileData->community_rank['rank'] ?? 'N/A' }}</h3>
                                        <p>Community Rank</p>
                                        <small class="text-warning">Top {{ $profileData->community_rank['percentile'] ?? 0 }}%</small>
                                    </div>
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
                                <div class="metric-value">{{ $profileData->personal_stats['total_waste_reported'] ?? '0' }} kg</div>
                                <div class="metric-label">Total Waste Reported</div>
                            </div>
                            
                            <div class="metric">
                                <div class="metric-value">{{ $profileData->waste_impact['total_waste_collected'] ?? '0' }} kg</div>
                                <div class="metric-label">Total Waste Collected</div>
                            </div>
                            
                            
                            
                            <div class="metric">
                                <div class="metric-value">
                                    <span class="badge bg-primary">{{ $profileData->waste_impact['today_reports'] ?? 0 }}</span>
                                    <span class="badge bg-success">{{ $profileData->waste_impact['today_collections'] ?? 0 }}</span>
                                </div>
                                <div class="metric-label">Today: Reports | Collections</div>
                            </div>
                        </div>
                        
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
                                    @if($request['status'] === 'pending')
                                    <button class="btn btn-sm btn-primary submit-collection-btn" 
                                            data-collection-id="{{ $request['collection_id'] }}"
                                            data-waste-type="{{ $request['waste_type'] }}"
                                            data-location="{{ $request['location'] }}"
                                            data-expected-weight="{{ $request['expected_weight'] }}">
                                        <i class="fas fa-check"></i> Submit Collection
                                    </button>
                                    @elseif($request['status'] === 'submitted')
                                    <small class="text-warning"><i class="fas fa-clock"></i> Awaiting Admin Verification</small>
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
                        <div class="card-header">
                            <h3 class="mb-0">
                                <i class="fas fa-file-alt"></i> My Waste Reports
                            </h3>
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
                                <i class="fas fa-info-circle text-muted"></i>
                                <p>No waste reports yet</p>
                                <small class="text-muted">Your reported waste will appear here</small>
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
                                    @if(isset($event['time']))
                                    <p><i class="fas fa-clock"></i> {{ date('g:i A', strtotime($event['time'])) }}</p>
                                    @endif
                                    
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
                            <i class="fas fa-history"></i> Today's Community Activity History
                            <small class="text-muted">({{ now()->format('F j, Y') }})</small>
                            <small id="refreshIndicator" class="text-success ms-2" style="display: none;">
                                <i class="fas fa-sync fa-spin"></i> Updating...
                            </small>
                        </h3>
                        
                        <div class="activity-feed-simple" id="activityFeed">
                            @forelse($dashboardData['recent_community_activity'] ?? [] as $activity)
                            <div class="activity-item-simple">
                                <div class="activity-status-icon status-{{ $activity['status'] }}">
                                    <i class="{{ $activity['icon'] }}"></i>
                                </div>
                                <div class="activity-content-main">
                                    <div class="activity-header-simple">
                                        <span class="status-badge badge-{{ $activity['color'] }}">
                                            {{ ucfirst($activity['status']) }}
                                        </span>
                                        <span class="activity-time-simple">{{ $activity['time_ago'] }}</span>
                                    </div>
                                    <p class="activity-message-main">{{ $activity['message'] }}</p>
                                    
                                    <!-- Enhanced Activity Details -->
                                    @if(isset($activity['detailed_status']) && count($activity['detailed_status']) > 0)
                                    <div class="activity-detailed-info mt-2">
                                        @foreach($activity['detailed_status'] as $detail)
                                        <div class="status-detail-item">
                                            <small class="text-muted">{{ $detail }}</small>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                    
                                    <div class="activity-details-simple">
                                        <span class="location-info">
                                            <i class="fas fa-map-marker-alt"></i> {{ $activity['location'] }}
                                        </span>
                                        <span class="amount-info">
                                            <i class="fas fa-weight"></i> {{ $activity['amount'] }}{{ $activity['unit'] ?? 'kg' }}
                                        </span>
                                        <span class="waste-type-info">
                                            <i class="fas fa-recycle"></i> {{ $activity['waste_type'] }}
                                        </span>
                                    </div>
                                </div>
                                @if($activity['can_collect'])
                                <div class="activity-action-simple">
                                    <button class="btn btn-sm btn-success collect-btn" data-waste-id="{{ $activity['id'] }}">
                                        <i class="fas fa-plus"></i> Collect
                                    </button>
                                </div>
                                @endif
                            </div>
                            @empty
                            <div class="empty-state-simple">
                                <i class="fas fa-calendar-day text-muted"></i>
                                <h5>No Activity Today</h5>
                                <p class="text-muted">No waste reports have been made today.</p>
                                <small class="text-muted">Be the first to report waste and help your community!</small>
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
                                <div class="metric-icon"><i class="fas fa-calendar-day"></i></div>
                                <div class="metric-details">
                                    <div class="metric-value">{{ $dashboardData['global_impact']['today_collected'] ?? '0' }}kg</div>
                                    <div class="metric-label">Today's Collections</div>
                                </div>
                            </div>
                            
                        </div>
                        
                        
                    </div>
                </div>
                <div class="impact-message mt-4">
                            <div class="alert alert-success" style="text-align: center;">
                                <i class="fas fa-heart"></i>
                                Together, we're making a difference! Every report counts towards a cleaner environment.
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
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            waste_report_id: wasteId
                        })
                    })
                    .then(response => {
                        const contentType = response.headers.get('content-type');
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        if (!contentType || !contentType.includes('application/json')) {
                            return response.text().then(text => {
                                console.error('Received non-JSON response:', text);
                                throw new Error('Server returned an error page instead of JSON response');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            // Update button to show success
                            btn.innerHTML = '<i class="fas fa-check"></i> Requested';
                            btn.classList.remove('btn-success');
                            btn.classList.add('btn-secondary');
                            btn.disabled = true;
                            
                            // Show success message
                            showNotification(data.message || 'Collection request submitted successfully!', 'success');
                            
                            // Update the activity item to show assigned status
                            const activityItem = btn.closest('.activity-item-simple');
                            if (activityItem) {
                                const statusIcon = activityItem.querySelector('.activity-status-icon');
                                const statusBadge = activityItem.querySelector('.status-badge');
                                
                                // Update visual status
                                statusIcon.classList.remove('status-available');
                                statusIcon.classList.add('status-assigned');
                                statusBadge.classList.remove('badge-primary');
                                statusBadge.classList.add('badge-warning');
                                statusBadge.textContent = 'ASSIGNED';
                                
                                // Remove collect button completely
                                btn.parentElement.remove();
                            }
                        } else {
                            throw new Error(data.error || data.message || 'Something went wrong');
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
                
                // Debug logging
                console.log('Form data entries:');
                for (let [key, value] of formData.entries()) {
                    console.log(key, value);
                }
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                console.log('CSRF Token:', csrfToken);
                
                // Disable submit button
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
                
                fetch('/submit-collection', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    console.log('Response status:', response.status);
                    console.log('Response headers:', response.headers.get('content-type'));
                    
                    // Check if response is OK and has JSON content type
                    if (!response.ok) {
                        throw new Error(`Server error: ${response.status} ${response.statusText}`);
                    }
                    
                    const contentType = response.headers.get('content-type');
                    if (!contentType || !contentType.includes('application/json')) {
                        // Log response text for debugging
                        return response.text().then(text => {
                            console.log('Non-JSON response:', text);
                            throw new Error('Server returned non-JSON response. Please check if you are logged in.');
                        });
                    }
                    
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Close modal
                        const modal = bootstrap.Modal.getInstance(document.getElementById('submitCollectionModal'));
                        modal.hide();
                        
                        // Show success message
                        showNotification('Collection submitted successfully! You earned ' + (data.coins_earned || 10) + ' eco coins!', 'success');
                        
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
    
    <script>
        // Real-time dashboard updates
        document.addEventListener('DOMContentLoaded', function () {
            // Auto-refresh activity feed every 30 seconds for real-time updates
            setInterval(function() {
                if (document.hasFocus()) {
                    refreshActivityFeed();
                }
            }, 30000); // 30 seconds
            
            // Initial setup
            attachCollectButtonListeners();
            
            // Animate progress bars
            animateProgressBars();
            
            // Animate counters
            animateCounters();
        });

        function attachCollectButtonListeners() {
            // Remove existing listeners to prevent duplicates
            document.querySelectorAll('.collect-btn').forEach(btn => {
                btn.replaceWith(btn.cloneNode(true));
            });
            
            // Attach collect button functionality
            document.querySelectorAll('.collect-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const wasteId = this.getAttribute('data-waste-id');
                    if (wasteId) {
                        requestCollection(wasteId, this);
                    }
                });
            });
        }

        function requestCollection(wasteId, button) {
            // Disable button and show loading
            button.disabled = true;
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
            
            fetch('/request-collection', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    waste_report_id: wasteId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showNotification('Collection request submitted successfully!', 'success');
                    // Refresh activity feed to show updated status
                    setTimeout(() => {
                        refreshActivityFeed();
                    }, 1000);
                } else {
                    showNotification(data.error || 'Failed to request collection', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('An error occurred while requesting collection', 'error');
            })
            .finally(() => {
                // Re-enable button
                button.disabled = false;
                button.innerHTML = originalText;
            });
        }
        
        function refreshActivityFeed() {
            console.log('Refreshing activity feed...');
            
            // Show refresh indicator
            const refreshIndicator = document.getElementById('refreshIndicator');
            if (refreshIndicator) {
                refreshIndicator.style.display = 'inline';
            }
            
            fetch('/home/community-activity', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    updateActivityFeed(data.activity);
                    console.log('Activity feed updated successfully');
                } else {
                    console.error('Failed to refresh activity feed:', data.error);
                }
            })
            .catch(error => {
                console.error('Error refreshing activity feed:', error);
            })
            .finally(() => {
                // Hide refresh indicator
                if (refreshIndicator) {
                    setTimeout(() => {
                        refreshIndicator.style.display = 'none';
                    }, 500);
                }
            });
        }

        function updateActivityFeed(activities) {
            const activityFeed = document.getElementById('activityFeed');
            if (!activityFeed) return;

            if (activities.length === 0) {
                activityFeed.innerHTML = `
                    <div class="empty-state-simple">
                        <i class="fas fa-calendar-day text-muted"></i>
                        <h5>No Activity Today</h5>
                        <p class="text-muted">No waste reports have been made today.</p>
                        <small class="text-muted">Be the first to report waste and help your community!</small>
                    </div>
                `;
                return;
            }

            let html = '';
            activities.forEach(activity => {
                html += `
                    <div class="activity-item-simple">
                        <div class="activity-status-icon status-${activity.status}">
                            <i class="${activity.icon}"></i>
                        </div>
                        <div class="activity-content-main">
                            <div class="activity-header-simple">
                                <span class="status-badge badge-${activity.color}">
                                    ${activity.status.charAt(0).toUpperCase() + activity.status.slice(1)}
                                </span>
                                <span class="activity-time-simple">${activity.time_ago}</span>
                            </div>
                            <p class="activity-message-main">${activity.message}</p>
                            <div class="activity-details-simple">
                                <span class="location-info">
                                    <i class="fas fa-map-marker-alt"></i> ${activity.location}
                                </span>
                                <span class="amount-info">
                                    <i class="fas fa-weight"></i> ${activity.amount}kg
                                </span>
                                <span class="waste-type-info">
                                    <i class="fas fa-recycle"></i> ${activity.waste_type}
                                </span>
                                ${activity.collector_name ? `
                                <span class="collector-info">
                                    <i class="fas fa-user"></i> ${activity.collector_name}
                                </span>
                                ` : ''}
                            </div>
                        </div>
                        ${activity.can_collect ? `
                        <div class="activity-action-simple">
                            <button class="btn btn-sm btn-success collect-btn" data-waste-id="${activity.id}">
                                <i class="fas fa-plus"></i> Collect
                            </button>
                        </div>
                        ` : ''}
                    </div>
                `;
            });

            activityFeed.innerHTML = html;
            
            // Reattach event listeners for collect buttons
            attachCollectButtonListeners();
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
