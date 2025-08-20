<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2c3338;
            color: #ffffff;
            min-height: 100vh;
            margin: 0;
            font-family: 'Segoe UI', sans-serif;
        }

        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }

        .stats-card {
            background: linear-gradient(145deg, #1a1d20, #2c3338);
            border: 1px solid #3a4147;
            border-radius: 15px;
            padding: 25px;
            margin-bottom: 25px;
            color: #ffffff;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.05), transparent);
            transition: left 0.5s;
        }

        .stats-card:hover::before {
            left: 100%;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.5);
            border-color: #4a5157;
        }

        .stats-icon {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #ffffff;
        }

        .stats-number {
            font-size: 2.5rem;
            font-weight: bold;
            margin: 10px 0;
            color: #ffffff;
        }

        .stats-label {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 500;
            color: #e0e0e0;
        }

        .welcome-card {
            background: linear-gradient(145deg, #1a1d20, #2c3338);
            border: 1px solid #3a4147;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            margin-top: 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }

        .gradient-text {
            color: #ffffff;
            font-weight: bold;
        }

        .dashboard-title {
            text-align: center;
            margin-bottom: 40px;
            font-size: 2.5rem;
            font-weight: bold;
            color: #ffffff;
        }

        .eco-stats {
            background: linear-gradient(145deg, #1a1d20, #2c3338);
            border-left: 4px solid #28a745;
        }

        .eco-stats .stats-icon {
            color: #28a745;
        }

        .user-stats {
            background: linear-gradient(145deg, #1a1d20, #2c3338);
            border-left: 4px solid #007bff;
        }

        .user-stats .stats-icon {
            color: #007bff;
        }

        .product-stats {
            background: linear-gradient(145deg, #1a1d20, #2c3338);
            border-left: 4px solid #fd7e14;
        }

        .product-stats .stats-icon {
            color: #fd7e14;
        }

        .activity-stats {
            background: linear-gradient(145deg, #1a1d20, #2c3338);
            border-left: 4px solid #20c997;
        }

        .activity-stats .stats-icon {
            color: #20c997;
        }

        .text-light {
            color: #b0b0b0 !important;
        }

        small.text-light {
            font-size: 0.85rem;
        }

        .welcome-card h3 {
            color: #ffffff;
            margin-bottom: 20px;
        }

        .welcome-card p {
            color: #e0e0e0;
        }

        .welcome-card .col-md-4 p {
            color: #b0b0b0;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    @include('admin.adminsidebar')

    <div class="content-wrapper">
        <div class="container-fluid">
            <h1 class="dashboard-title">📊 Admin Dashboard</h1>

            @php
                // Get statistics from all tables
                $totalUsers = DB::table('logins')->count();
                $totalEvents = DB::table('events')->count();
                $totalProducts = DB::table('products')->count();
                $totalPurchases = DB::table('purchases')->count();
                $totalVolunteers = DB::table('volunteers')->count();
                $totalWasteReports = DB::table('wastereport')->count();
                $totalEcoCoinsEarned = DB::table('coins')->where('eco_coin_value', '>', 0)->sum('eco_coin_value');
                $totalEcoCoinsSpent = abs(DB::table('coins')->where('eco_coin_value', '<', 0)->sum('eco_coin_value'));
                $pendingPurchases = DB::table('purchases')->where('status', 'pending')->count();
                $confirmedPurchases = DB::table('purchases')->where('status', 'confirmed')->count();
                $totalStock = DB::table('products')->sum('stock');
                $recentUsers = DB::table('logins')->where('created_at', '>=', now()->subDays(30))->count();
            @endphp

            <!-- User & Community Stats -->
            <div class="row">
                <div class="col-md-3">
                    <div class="stats-card user-stats text-center">
                        <i class="fas fa-users stats-icon"></i>
                        <div class="stats-number">{{ number_format($totalUsers) }}</div>
                        <div class="stats-label">Total Users</div>
                        <small class="text-light">{{ $recentUsers }} new this month</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card activity-stats text-center">
                        <i class="fas fa-hands-helping stats-icon"></i>
                        <div class="stats-number">{{ number_format($totalVolunteers) }}</div>
                        <div class="stats-label">Volunteers</div>
                        <small class="text-light">Making a difference</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card eco-stats text-center">
                        <i class="fas fa-recycle stats-icon"></i>
                        <div class="stats-number">{{ number_format($totalWasteReports) }}</div>
                        <div class="stats-label">Waste Reports</div>
                        <small class="text-light">Environmental impact</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card activity-stats text-center">
                        <i class="fas fa-calendar-alt stats-icon"></i>
                        <div class="stats-number">{{ number_format($totalEvents) }}</div>
                        <div class="stats-label">Events</div>
                        <small class="text-light">Community activities</small>
                    </div>
                </div>
            </div>

            <!-- Eco-Coins & Financial Stats -->
            <div class="row">
                <div class="col-md-4">
                    <div class="stats-card eco-stats text-center">
                        <i class="fas fa-coins stats-icon"></i>
                        <div class="stats-number">{{ number_format($totalEcoCoinsEarned) }}</div>
                        <div class="stats-label">Eco-Coins Earned</div>
                        <small class="text-light">Total rewards distributed</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card product-stats text-center">
                        <i class="fas fa-shopping-cart stats-icon"></i>
                        <div class="stats-number">{{ number_format($totalEcoCoinsSpent) }}</div>
                        <div class="stats-label">Eco-Coins Spent</div>
                        <small class="text-light">Rewards redeemed</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card user-stats text-center">
                        <i class="fas fa-chart-line stats-icon"></i>
                        <div class="stats-number">
                            {{ $totalEcoCoinsEarned > 0 ? number_format(($totalEcoCoinsSpent / $totalEcoCoinsEarned) * 100, 1) : 0 }}%
                        </div>
                        <div class="stats-label">Redemption Rate</div>
                        <small class="text-light">Engagement metric</small>
                    </div>
                </div>
            </div>

            <!-- Products & Orders Stats -->
            <div class="row">
                <div class="col-md-3">
                    <div class="stats-card product-stats text-center">
                        <i class="fas fa-box stats-icon"></i>
                        <div class="stats-number">{{ number_format($totalProducts) }}</div>
                        <div class="stats-label">Products</div>
                        <small class="text-light">Available rewards</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card eco-stats text-center">
                        <i class="fas fa-warehouse stats-icon"></i>
                        <div class="stats-number">{{ number_format($totalStock) }}</div>
                        <div class="stats-label">Total Stock</div>
                        <small class="text-light">Items in inventory</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card activity-stats text-center">
                        <i class="fas fa-clock stats-icon"></i>
                        <div class="stats-number">{{ number_format($pendingPurchases) }}</div>
                        <div class="stats-label">Pending Orders</div>
                        <small class="text-light">Awaiting processing</small>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card user-stats text-center">
                        <i class="fas fa-check-circle stats-icon"></i>
                        <div class="stats-number">{{ number_format($confirmedPurchases) }}</div>
                        <div class="stats-label">Completed Orders</div>
                        <small class="text-light">Successfully delivered</small>
                    </div>
                </div>
            </div>

            <!-- Welcome Card -->
            <div class="row">
                <div class="col-12">
                    <div class="welcome-card">
                        <h3>🌟 Welcome back, {{ Auth::guard('admin')->user()->email }}!</h3>
                        <p class="mt-3">Your dashboard is showing great engagement! The community is actively
                            participating in environmental initiatives.</p>
                        <div class="row mt-4">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-leaf" style="font-size: 2rem; color: #28a745;"></i>
                                    <p class="mt-2">Eco-Friendly Impact</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-globe-americas" style="font-size: 2rem; color: #17a2b8;"></i>
                                    <p class="mt-2">Global Community</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-award" style="font-size: 2rem; color: #ffc107;"></i>
                                    <p class="mt-2">Achievement System</p>
                                </div>
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