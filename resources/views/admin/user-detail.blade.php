<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $user->first_name && $user->last_name ? trim($user->first_name . ' ' . $user->last_name) : $user->username }} - User Details</title>
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

        .profile-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .profile-img-large {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid white;
        }

        .stats-card {
            background-color: #2c3338;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }

        .stats-card i {
            font-size: 2rem;
            margin-bottom: 10px;
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

        .nav-pills .nav-link {
            background-color: #495057;
            color: #ffffff;
            margin-right: 5px;
        }

        .nav-pills .nav-link.active {
            background-color: #007bff;
        }

        .text-success { color: #28a745 !important; }
        .text-warning { color: #ffc107 !important; }
        .text-info { color: #17a2b8 !important; }
        .text-danger { color: #dc3545 !important; }

        h1, h2, h3, h4, h5, .card-title {
            color: #ffffff !important;
        }

        .waste-type-card {
            background-color: #2c3338;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
        }

        .social-links a {
            color: #17a2b8;
            text-decoration: none;
        }

        .social-links a:hover {
            color: #ffffff;
        }
    </style>
</head>

<body>
    @include('admin.adminsidebar')

    <div class="content-wrapper">
        <div class="container-fluid">
            <!-- Back Button -->
            <div class="mb-3">
                <a href="{{ route('admin.user.details') }}" class="btn btn-outline-light">
                    <i class="fas fa-arrow-left me-2"></i>Back to User List
                </a>
            </div>

            <!-- Profile Header -->
            <div class="profile-header">
                <div class="row align-items-center">
                    <div class="col-md-2">
                        @if($user->profile_picture)
                            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="Profile" class="profile-img-large">
                        @else
                            <div class="bg-white rounded-circle d-flex align-items-center justify-content-center profile-img-large">
                                <i class="fas fa-user fa-3x text-muted"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-6">
                        <h1 class="mb-2">
                            @if($user->first_name || $user->last_name)
                                {{ trim($user->first_name . ' ' . $user->last_name) }}
                            @else
                                {{ $user->username }}
                            @endif
                        </h1>
                        <p class="mb-1"><i class="fas fa-envelope me-2"></i>{{ $user->email }}</p>
                        @if($user->phone)
                            <p class="mb-1"><i class="fas fa-phone me-2"></i>{{ $user->phone }}</p>
                        @endif
                        @if($user->location)
                            <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>{{ $user->location }}</p>
                        @endif
                        @if($user->organization)
                            <p class="mb-1"><i class="fas fa-building me-2"></i>{{ $user->organization }}</p>
                        @endif
                        <p class="mb-0"><i class="fas fa-calendar me-2"></i>Joined {{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</p>
                    </div>
                    <div class="col-md-4 text-end">
                        @if($user->status)
                            <span class="badge bg-primary mb-2">{{ ucfirst($user->status) }}</span>
                        @else
                            <span class="badge bg-success mb-2">Active</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stats-card">
                        <i class="fas fa-coins text-warning"></i>
                        <h4>{{ number_format($user->eco_coins) }}</h4>
                        <p class="mb-0">Total EcoCoins</p>
                        <small class="text-muted">Total Tokens: {{ number_format($user->total_token) }}</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <i class="fas fa-recycle text-success"></i>
                        <h4>{{ number_format($user->waste_reports_count) }}</h4>
                        <p class="mb-0">Waste Reports</p>
                        <small class="text-muted">{{ number_format($user->carbon_footprint_saved, 1) }} kg CO₂ saved</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <i class="fas fa-shopping-cart text-info"></i>
                        <h4>{{ number_format($user->purchases_count) }}</h4>
                        <p class="mb-0">Purchases</p>
                        <small class="text-muted">{{ number_format($user->coins_spent) }} coins spent</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card">
                        <i class="fas fa-star text-warning"></i>
                        <h4>{{ number_format($user->points_earned) }}</h4>
                        <p class="mb-0">Points Earned</p>
                        <small class="text-muted">{{ $user->volunteer_hours }} volunteer hours</small>
                    </div>
                </div>
            </div>

            <!-- Tabbed Content -->
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-pills" id="userTabs" role="tablist">
                        <li class="nav-item">
                            <button class="nav-link active" id="profile-tab" data-bs-toggle="pill" data-bs-target="#profile" type="button" role="tab">
                                <i class="fas fa-user me-2"></i>Profile Details
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="waste-tab" data-bs-toggle="pill" data-bs-target="#waste" type="button" role="tab">
                                <i class="fas fa-recycle me-2"></i>Waste Reports
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="coins-tab" data-bs-toggle="pill" data-bs-target="#coins" type="button" role="tab">
                                <i class="fas fa-coins me-2"></i>Coin History
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" id="purchases-tab" data-bs-toggle="pill" data-bs-target="#purchases" type="button" role="tab">
                                <i class="fas fa-shopping-cart me-2"></i>Purchases
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="userTabsContent">
                        <!-- Profile Details Tab -->
                        <div class="tab-pane fade show active" id="profile" role="tabpanel">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Personal Information</h5>
                                    <table class="table table-dark table-borderless">
                                        <tr>
                                            <td><strong>Username:</strong></td>
                                            <td>{{ $user->username ?? 'Not set' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Full Name:</strong></td>
                                            <td>{{ trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: 'Not set' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Email:</strong></td>
                                            <td>{{ $user->email }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Phone:</strong></td>
                                            <td>{{ $user->phone ?? 'Not set' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Date of Birth:</strong></td>
                                            <td>{{ $user->date_of_birth ? \Carbon\Carbon::parse($user->date_of_birth)->format('M d, Y') : 'Not set' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Gender:</strong></td>
                                            <td>{{ $user->gender ?? 'Not specified' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Location:</strong></td>
                                            <td>{{ $user->location ?? 'Not set' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Organization:</strong></td>
                                            <td>{{ $user->organization ?? 'Not set' }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Website:</strong></td>
                                            <td>
                                                @if($user->website)
                                                    <a href="{{ $user->website }}" target="_blank" class="text-info">{{ $user->website }}</a>
                                                @else
                                                    Not set
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <h5>Activity & Preferences</h5>
                                    
                                    @if($user->bio)
                                    <div class="mb-3">
                                        <strong>Bio:</strong>
                                        <p class="mt-2">{{ $user->bio }}</p>
                                    </div>
                                    @endif

                                    @if($user->social_links)
                                    <div class="mb-3 social-links">
                                        <strong>Social Links:</strong>
                                        <div class="mt-2">
                                            @php
                                                $socialLinks = is_string($user->social_links) ? json_decode($user->social_links, true) : $user->social_links;
                                            @endphp
                                            @if($socialLinks && is_array($socialLinks))
                                                @foreach($socialLinks as $platform => $url)
                                                    @if($url)
                                                        <div><i class="fab fa-{{ strtolower($platform) }} me-2"></i><a href="{{ $url }}" target="_blank">{{ ucfirst($platform) }}</a></div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <span class="text-muted">No social links</span>
                                            @endif
                                        </div>
                                    </div>
                                    @endif

                                    @if($user->preferred_causes)
                                    <div class="mb-3">
                                        <strong>Preferred Causes:</strong>
                                        <div class="mt-2">
                                            @php
                                                $preferredCauses = is_string($user->preferred_causes) ? json_decode($user->preferred_causes, true) : $user->preferred_causes;
                                            @endphp
                                            @if($preferredCauses && is_array($preferredCauses))
                                                @foreach($preferredCauses as $cause)
                                                    <span class="badge bg-primary me-1">{{ $cause }}</span>
                                                @endforeach
                                            @else
                                                <span class="text-muted">No preferred causes set</span>
                                            @endif
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Waste Reports Tab -->
                        <div class="tab-pane fade" id="waste" role="tabpanel">
                            <div class="row mb-4">
                                <div class="col-md-12">
                                    <h5>Waste Reports Summary</h5>
                                    <div class="row">
                                        @foreach($wasteByType as $wasteType)
                                        <div class="col-md-4 mb-3">
                                            <div class="waste-type-card">
                                                <h6 class="text-capitalize">{{ $wasteType->waste_type }}</h6>
                                                <div class="d-flex justify-content-between">
                                                    <span>{{ $wasteType->count }} reports</span>
                                                    <span>{{ number_format($wasteType->total_amount, 1) }} kg</span>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <h5>Recent Waste Reports</h5>
                            <div class="table-responsive">
                                <table class="table table-dark table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Location</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($wasteReports as $report)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($report->created_at)->format('M d, Y H:i') }}</td>
                                            <td>
                                                <span class="badge bg-info text-capitalize">{{ $report->waste_type }}</span>
                                            </td>
                                            <td>{{ number_format($report->amount, 1) }} {{ $report->unit ?? 'kg' }}</td>
                                            <td>{{ $report->location ?? 'Not specified' }}</td>
                                            <td>{{ Str::limit($report->description ?? '', 50) }}</td>
                                            <td>
                                                <span class="badge {{ $report->status == 'verified' ? 'bg-success' : 'bg-warning' }}">
                                                    {{ ucfirst($report->status ?? 'pending') }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6" class="text-center py-4">No waste reports found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Coin History Tab -->
                        <div class="tab-pane fade" id="coins" role="tabpanel">
                            <h5>Recent Coin Transactions</h5>
                            <div class="table-responsive">
                                <table class="table table-dark table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Reason</th>
                                            <th>Amount</th>
                                            <th>Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($coinTransactions as $transaction)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y H:i') }}</td>
                                            <td>{{ $transaction->reason }}</td>
                                            <td class="{{ $transaction->eco_coin_value >= 0 ? 'text-success' : 'text-danger' }}">
                                                {{ $transaction->eco_coin_value >= 0 ? '+' : '' }}{{ number_format($transaction->eco_coin_value) }}
                                            </td>
                                            <td>
                                                <span class="badge {{ $transaction->eco_coin_value >= 0 ? 'bg-success' : 'bg-danger' }}">
                                                    {{ $transaction->eco_coin_value >= 0 ? 'Earned' : 'Spent' }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">No coin transactions found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Purchases Tab -->
                        <div class="tab-pane fade" id="purchases" role="tabpanel">
                            <h5>Purchase History</h5>
                            <div class="table-responsive">
                                <table class="table table-dark table-hover">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Product</th>
                                            <th>Delivery Info</th>
                                            <th>Coins Spent</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($purchases as $purchase)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($purchase->created_at)->format('M d, Y H:i') }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($purchase->product_image)
                                                        <img src="{{ asset($purchase->product_image) }}" alt="Product" style="width: 40px; height: 40px; object-fit: cover;" class="rounded me-2">
                                                    @endif
                                                    <div>
                                                        <div>{{ $purchase->product_name ?? 'Product not found' }}</div>
                                                        @if($purchase->product_description)
                                                            <small class="text-muted">{{ Str::limit($purchase->product_description, 50) }}</small>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <div><strong>{{ $purchase->name }}</strong></div>
                                                <div><i class="fas fa-map-marker-alt me-1"></i>{{ $purchase->address }}</div>
                                                <div><i class="fas fa-phone me-1"></i>{{ $purchase->mobile }}</div>
                                            </td>
                                            <td class="text-warning">{{ number_format($purchase->eco_coins_spent) }}</td>
                                            <td>
                                                <span class="badge {{ $purchase->status == 'delivered' ? 'bg-success' : ($purchase->status == 'confirmed' ? 'bg-info' : 'bg-warning') }}">
                                                    {{ ucfirst($purchase->status ?? 'pending') }}
                                                </span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4">No purchases found</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
