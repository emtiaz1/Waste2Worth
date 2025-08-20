<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details - Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2c3338;
            color: #ffffff;
        }

        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }

        .card {
            background-color: #1a1d20;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .card-body {
            color: #ffffff;
        }

        .table-dark {
            background-color: #1a1d20;
            --bs-table-bg: #1a1d20;
        }

        .table-dark th,
        .table-dark td {
            border-color: #495057;
        }

        .badge {
            font-size: 0.75rem;
        }

        .btn-outline-light {
            border-color: #6c757d;
        }

        .btn-outline-light:hover {
            background-color: #6c757d;
            border-color: #6c757d;
        }

        .profile-img {
            width: 40px;
            height: 40px;
            object-fit: cover;
            border-radius: 50%;
        }

        .stats-badge {
            background-color: #2c3338;
            color: #ffffff;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 0.8rem;
        }

        .text-success { color: #28a745 !important; }
        .text-warning { color: #ffc107 !important; }
        .text-info { color: #17a2b8 !important; }
        .text-danger { color: #dc3545 !important; }

        h2, h3, h4, h5, .card-title {
            color: #ffffff !important;
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
        }

        .filter-section {
            background-color: #1a1d20;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    @include('admin.adminsidebar')

    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Header -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h2 class="mb-1">User Management</h2>
                            <p class="text-muted mb-0">Comprehensive view of all registered users</p>
                        </div>
                        <div class="d-flex gap-2">
                            <span class="badge bg-info">Total Users: {{ $users->count() }}</span>
                            <span class="badge bg-success">Active Today: {{ $users->where('created_at', '>=', now()->startOfDay())->count() }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Overview Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-users fa-2x text-primary mb-3"></i>
                            <h4>{{ $users->count() }}</h4>
                            <p class="mb-0">Total Users</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-user-check fa-2x text-success mb-3"></i>
                            <h4>{{ $users->whereNotNull('username')->count() }}</h4>
                            <p class="mb-0">With Profiles</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-coins fa-2x text-warning mb-3"></i>
                            <h4>{{ $users->where('eco_coins', '>', 0)->count() }}</h4>
                            <p class="mb-0">Have EcoCoins</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <i class="fas fa-shopping-cart fa-2x text-info mb-3"></i>
                            <h4>{{ $users->where('purchases_count', '>', 0)->count() }}</h4>
                            <p class="mb-0">Made Purchases</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>All Users
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Contact Info</th>
                                    <th>Activity Stats</th>
                                    <th>EcoCoins</th>
                                    <th>Waste Reports</th>
                                    <th>Purchases</th>
                                    <th>Joined</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($user->profile_picture)
                                                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" class="profile-img me-3">
                                            @else
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">
                                                    @if($user->first_name || $user->last_name)
                                                        {{ trim($user->first_name . ' ' . $user->last_name) }}
                                                    @else
                                                        {{ $user->username }}
                                                    @endif
                                                </div>
                                                <small class="text-muted">{{ $user->email }}</small>
                                                @if($user->location)
                                                    <div><small class="text-info"><i class="fas fa-map-marker-alt"></i> {{ $user->location }}</small></div>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($user->phone)
                                            <div><i class="fas fa-phone me-1"></i> {{ $user->phone }}</div>
                                        @endif
                                        @if($user->website)
                                            <div><i class="fas fa-globe me-1"></i> <a href="{{ $user->website }}" target="_blank" class="text-info">Website</a></div>
                                        @endif
                                        @if($user->organization)
                                            <div><i class="fas fa-building me-1"></i> {{ $user->organization }}</div>
                                        @endif
                                        @if($user->status)
                                            <span class="badge bg-primary">{{ ucfirst($user->status) }}</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            @if($user->points_earned)
                                            <span class="stats-badge">
                                                <i class="fas fa-star text-warning"></i> {{ $user->points_earned ?? 0 }} pts
                                            </span>
                                            @endif
                                            @if($user->community_events_attended)
                                            <span class="stats-badge">
                                                <i class="fas fa-calendar text-info"></i> {{ $user->community_events_attended }} events
                                            </span>
                                            @endif
                                            @if($user->volunteer_hours)
                                            <span class="stats-badge">
                                                <i class="fas fa-hands-helping text-success"></i> {{ $user->volunteer_hours }}h
                                            </span>
                                            @endif
                                            @if(!$user->points_earned && !$user->community_events_attended && !$user->volunteer_hours)
                                            <span class="text-muted">No activity data</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-success">{{ number_format($user->eco_coins) }}</span>
                                            @if($user->coins_spent > 0)
                                                <small class="text-muted">Spent: {{ number_format($user->coins_spent) }}</small>
                                            @endif
                                            @if($user->total_token)
                                                <small class="text-info">Tokens: {{ number_format($user->total_token) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $user->waste_reports_count }}</span>
                                            @if($user->carbon_footprint_saved > 0)
                                                <small class="text-success">{{ $user->carbon_footprint_saved }} kg CO₂</small>
                                            @else
                                                <small class="text-muted">No CO₂ data</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $user->purchases_count }}</span>
                                            @if($user->coins_spent > 0)
                                                <small class="text-muted">{{ number_format($user->coins_spent) }} coins</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div>{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->format('M d, Y') : 'N/A' }}</div>
                                        <small class="text-muted">{{ $user->created_at ? \Carbon\Carbon::parse($user->created_at)->diffForHumans() : '' }}</small>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1">
                                            <a href="{{ route('admin.user.detail', $user->id) }}" class="btn btn-sm btn-outline-light" title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="fas fa-users fa-3x mb-3"></i>
                                            <p>No users found</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
