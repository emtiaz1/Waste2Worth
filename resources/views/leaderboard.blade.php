<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Leaderboard | Waste2Worth</title>
    <link rel="stylesheet" href="{{ asset('css/appbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/leaderboard.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    @include('layouts.appbar')
    <div class="layout" id="mainLayout">
        <div class="main-content">
            <main class="main-content">
                <!-- Community Leaderboard Section - Main Focus -->
                <section class="leaderboard-main-section">
                    <div class="container-fluid">
                        <div class="leaderboard-card">
                            <div class="leaderboard-header">
                                <h1 class="leaderboard-title">
                                    <i class="fas fa-trophy"></i> Community Leaderboard
                                    <span class="live-indicator pulsing">●</span>
                                </h1>
                                <div class="leaderboard-meta">
                                    <span class="participant-count">{{ $leaderboardData->count() }} Active Contributors</span>
                                    <button class="btn btn-outline-light btn-sm refresh-btn" onclick="refreshLeaderboard()">
                                        <i class="fas fa-sync-alt"></i> Refresh
                                    </button>
                                </div>
                            </div>

                            <!-- Top 3 Contributors Podium -->
                            @if($leaderboardData->count() >= 3)
                            <div class="top-podium-section">
                                <div class="podium-container">
                                    @foreach($leaderboardData->take(3) as $index => $user)
                                    <div class="podium-winner podium-{{ $index + 1 }}">
                                        <div class="winner-rank">
                                            <i class="fas {{ $index === 0 ? 'fa-crown' : ($index === 1 ? 'fa-medal' : 'fa-award') }}"></i>
                                        </div>
                                        <div class="winner-avatar">
                                            @if($user->profile_picture)
                                                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->display_name ?? 'User' }}">
                                            @else
                                                <div class="avatar-placeholder-winner">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="winner-info">
                                            <h3 class="winner-name">{{ $user->display_name ?? $user->username ?? 'Anonymous' }}</h3>
                                            <div class="winner-score">{{ number_format($user->performance_score ?? 0, 0) }} pts</div>
                                            <div class="winner-location">{{ $user->location ?? 'Global' }}</div>
                                        </div>
                                        <div class="winner-stats">
                                            <div class="stat-badge coins">
                                                <i class="fas fa-coins"></i>
                                                {{ number_format($user->eco_coins ?? 0) }}
                                            </div>
                                            <div class="stat-badge reports">
                                                <i class="fas fa-file-alt"></i>
                                                {{ $user->reports_count ?? 0 }}
                                            </div>
                                            <div class="stat-badge waste">
                                                <i class="fas fa-weight-hanging"></i>
                                                {{ number_format($user->total_waste ?? 0, 1) }}kg
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            <!-- Full Leaderboard Table -->
                            <div class="leaderboard-table-wrapper">
                                <div class="table-responsive">
                                    <table class="table leaderboard-table">
                                        <thead>
                                            <tr>
                                                <th class="rank-col">Rank</th>
                                                <th class="user-col">Participant</th>
                                                <th class="coins-col">EcoCoins</th>
                                                <th class="reports-col">Reports</th>
                                                <th class="waste-col">Waste (kg)</th>
                                                <th class="collections-col">Collections</th>
                                                <th class="score-col">Score</th>
                                                <th class="badge-col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($leaderboardData as $index => $user)
                                            <tr class="participant-row {{ $index < 3 ? 'top-performer' : '' }}">
                                                <td class="rank-cell">
                                                    <div class="rank-display {{ $index < 3 ? 'medal-rank' : '' }}">
                                                        @if($index < 3)
                                                            <i class="fas {{ $index === 0 ? 'fa-trophy gold' : ($index === 1 ? 'fa-medal silver' : 'fa-award bronze') }}"></i>
                                                        @endif
                                                        <span class="rank-number">#{{ $index + 1 }}</span>
                                                    </div>
                                                </td>
                                                <td class="user-cell">
                                                    <div class="participant-info">
                                                        <div class="participant-avatar">
                                                            @if($user->profile_picture)
                                                                <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->display_name ?? 'User' }}">
                                                            @else
                                                                <div class="avatar-placeholder">
                                                                    <i class="fas fa-user"></i>
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="participant-details">
                                                            <div class="participant-name">{{ $user->display_name ?? $user->username ?? 'Anonymous' }}</div>
                                                            <div class="participant-location">{{ $user->location ?? 'Unknown Location' }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="coins-cell">
                                                    <div class="coin-display">
                                                        <i class="fas fa-coins coin-icon"></i>
                                                        <span class="coin-amount">{{ number_format($user->eco_coins ?? 0) }}</span>
                                                    </div>
                                                </td>
                                                <td class="reports-cell">
                                                    <span class="report-count">{{ $user->reports_count ?? 0 }}</span>
                                                </td>
                                                <td class="waste-cell">
                                                    <span class="waste-amount">{{ number_format($user->total_waste ?? 0, 1) }}</span>
                                                </td>
                                                <td class="collections-cell">
                                                    <span class="collection-count">{{ $user->collections_completed ?? 0 }}</span>
                                                </td>
                                                <td class="score-cell">
                                                    <div class="score-display">
                                                        <span class="score-number">{{ number_format($user->performance_score ?? 0, 0) }}</span>
                                                        <span class="score-label">pts</span>
                                                    </div>
                                                </td>
                                                <td class="badge-cell">
                                                    <span class="status-badge {{ ($user->badge['type'] ?? 'participant') }}">
                                                        <i class="{{ $user->badge['icon'] ?? 'fas fa-user' }}"></i>
                                                        {{ $user->badge['title'] ?? 'Participant' }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Recent Achievements Section -->
                <section class="achievements-main-section">
                    <div class="container-fluid">
                        <div class="achievements-header">
                            <h2 class="achievements-title">
                                <i class="fas fa-star"></i> Recent Achievements
                            </h2>
                            <span class="achievement-badge-count">{{ count($recentAchievements ?? []) }} this week</span>
                        </div>

                        @if(isset($recentAchievements) && count($recentAchievements) > 0)
                        <div class="achievements-grid">
                            @foreach($recentAchievements as $achievement)
                            <div class="achievement-item">
                                <div class="achievement-content">
                                    <div class="achievement-icon-wrapper">
                                        <div class="achievement-icon" style="background-color: {{ $achievement['color'] ?? '#007bff' }}20; color: {{ $achievement['color'] ?? '#007bff' }}">
                                            <i class="{{ $achievement['icon'] ?? 'fas fa-trophy' }}"></i>
                                        </div>
                                    </div>
                                    <div class="achievement-details">
                                        <h4 class="achievement-name">{{ $achievement['title'] ?? 'Achievement' }}</h4>
                                        <p class="achievement-desc">{{ $achievement['description'] ?? 'Great work!' }}</p>
                                        <div class="achievement-user-info">
                                            <div class="achievement-avatar">
                                                @if(($achievement['user']['avatar'] ?? null))
                                                    <img src="{{ asset('storage/' . $achievement['user']['avatar']) }}" alt="{{ $achievement['user']['name'] ?? 'User' }}">
                                                @else
                                                    <div class="avatar-placeholder-small">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="achievement-user-details">
                                                <span class="achievement-username">{{ $achievement['user']['name'] ?? 'Anonymous' }}</span>
                                                <span class="achievement-timestamp">{{ isset($achievement['timestamp']) ? $achievement['timestamp']->diffForHumans() : 'Recently' }}</span>
                                            </div>
                                        </div>
                                        <div class="achievement-metrics">
                                            @if(isset($achievement['stats']['reports']))
                                                <span class="metric">{{ $achievement['stats']['reports'] }} reports</span>
                                            @endif
                                            @if(isset($achievement['stats']['coins']))
                                                <span class="metric">{{ number_format($achievement['stats']['coins']) }} coins</span>
                                            @endif
                                            @if(isset($achievement['stats']['waste_amount']))
                                                <span class="metric">{{ number_format($achievement['stats']['waste_amount'], 1) }}kg waste</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="no-achievements-state">
                            <div class="no-achievements-content">
                                <i class="fas fa-trophy no-achievement-icon"></i>
                                <h3>No recent achievements</h3>
                                <p>Be the first to earn an achievement this week!</p>
                                <a href="{{ url('/home') }}" class="btn btn-success">
                                    <i class="fas fa-plus"></i> Start Contributing
                                </a>
                            </div>
                        </div>
                        @endif
                    </div>
                </section>

                <!-- Quick Stats Footer (Minimal) -->
                <section class="quick-stats-footer">
                    <div class="container-fluid">
                        <div class="stats-summary">
                            <div class="stat-item">
                                <span class="stat-number">{{ $statistics['total_participants'] ?? $leaderboardData->count() }}</span>
                                <span class="stat-label">Contributors</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ number_format($statistics['total_eco_coins'] ?? 0) }}</span>
                                <span class="stat-label">EcoCoins</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ number_format($statistics['total_waste_processed'] ?? 0, 1) }}kg</span>
                                <span class="stat-label">Waste Processed</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-number">{{ $statistics['total_collections'] ?? 0 }}</span>
                                <span class="stat-label">Collections</span>
                            </div>
                            <div class="update-info">
                                <i class="fas fa-clock"></i>
                                <span>Last updated: <strong id="lastUpdate">{{ now()->format('M d, Y h:i A') }}</strong></span>
                                <button class="mini-refresh-btn" onclick="refreshLeaderboard()">
                                    <i class="fas fa-sync-alt" id="refreshIcon"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- No Data State -->
                @if($leaderboardData->count() == 0)
                <section class="no-data-section">
                    <div class="container">
                        <div class="no-data-card">
                            <div class="no-data-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <h3>No participants yet</h3>
                            <p>Be the first to contribute and appear on the leaderboard!</p>
                            <a href="{{ url('/home') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Report Waste
                            </a>
                        </div>
                    </div>
                </section>
                @endif

                <!-- Footer -->
                
            </main>
        </div>
    </div>

    <!-- JavaScript for Real-time Updates -->
    <script>
    let refreshInterval;
    let lastUpdateTime = new Date();

    // Initialize real-time updates
    document.addEventListener('DOMContentLoaded', function() {
        // Set up automatic refresh every 30 seconds
        refreshInterval = setInterval(refreshLeaderboard, 30000);
        
        // Update last update time display
        updateLastUpdateDisplay();
    });

    // Refresh leaderboard data
    async function refreshLeaderboard() {
        const refreshBtn = document.querySelector('.refresh-btn');
        const refreshIcon = document.getElementById('refreshIcon');
        const refreshText = document.getElementById('refreshText');
        
        // Show loading state
        refreshBtn.disabled = true;
        refreshIcon.className = 'fas fa-spinner fa-spin';
        refreshText.textContent = 'Updating...';
        
        try {
            const response = await fetch('/leaderboard/api/data', {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                
                // Update statistics cards
                updateStatisticsCards(data.statistics);
                
                // Update last update time
                lastUpdateTime = new Date();
                updateLastUpdateDisplay();
                
                // Show success feedback
                showUpdateFeedback('success', 'Leaderboard updated successfully!');
                
                // Reload the page for complete update
                setTimeout(() => {
                    location.reload();
                }, 2000);
            } else {
                throw new Error('Failed to fetch data');
            }
        } catch (error) {
            console.error('Error refreshing leaderboard:', error);
            showUpdateFeedback('error', 'Failed to update leaderboard');
        } finally {
            // Reset button state
            setTimeout(() => {
                refreshBtn.disabled = false;
                refreshIcon.className = 'fas fa-sync-alt';
                refreshText.textContent = 'Refresh';
            }, 1000);
        }
    }

    // Update statistics cards with new data
    function updateStatisticsCards(statistics) {
        // Update participants
        const participantsElement = document.getElementById('totalParticipants');
        if (participantsElement && statistics.total_participants) {
            participantsElement.textContent = statistics.total_participants;
            animateCountUp(participantsElement);
        }
        
        // Update total eco coins
        const coinsElement = document.getElementById('totalCoins');
        if (coinsElement && statistics.total_eco_coins_distributed) {
            coinsElement.textContent = formatNumber(statistics.total_eco_coins_distributed);
            animateCountUp(coinsElement);
        }
        
        // Update waste processed
        const wasteElement = document.getElementById('totalWaste');
        if (wasteElement && statistics.total_waste_collected) {
            wasteElement.textContent = formatNumber(statistics.total_waste_collected, 1);
            animateCountUp(wasteElement);
        }
        
        // Update collections completed
        const collectionsElement = document.getElementById('completedCollections');
        if (collectionsElement && statistics.completed_collections) {
            collectionsElement.textContent = statistics.completed_collections;
            animateCountUp(collectionsElement);
        }
    }

    // Show update feedback
    function showUpdateFeedback(type, message) {
        const feedback = document.createElement('div');
        feedback.className = `update-feedback ${type}`;
        feedback.innerHTML = `
            <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
            <span>${message}</span>
        `;
        
        document.body.appendChild(feedback);
        
        // Remove feedback after 3 seconds
        setTimeout(() => {
            feedback.remove();
        }, 3000);
    }

    // Update last update time display
    function updateLastUpdateDisplay() {
        const lastUpdateElement = document.getElementById('lastUpdate');
        if (lastUpdateElement) {
            lastUpdateElement.textContent = lastUpdateTime.toLocaleString();
        }
    }

    // Animate count up effect
    function animateCountUp(element) {
        element.classList.add('count-up');
        setTimeout(() => {
            element.classList.remove('count-up');
        }, 500);
    }

    // Format number with commas
    function formatNumber(num, decimals = 0) {
        return Number(num).toLocaleString('en-US', {
            minimumFractionDigits: decimals,
            maximumFractionDigits: decimals
        });
    }

    // Clean up interval on page unload
    window.addEventListener('beforeunload', function() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
        }
    });
    </script>

    <style>
    /* Main Layout */
    .main-content {
        padding: 0;
        margin: 0;
        background: #f5f6fa;
    }

    /* Leaderboard Main Section */
    .leaderboard-main-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 3rem 0;
        margin-bottom: 0;
    }

    .leaderboard-card {
        background: rgba(255, 255, 255, 0.95);
        border-radius: 20px;
        padding: 2rem;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        backdrop-filter: blur(10px);
    }

    .leaderboard-header {
        text-align: center;
        margin-bottom: 2rem;
        border-bottom: 2px solid #f8f9fa;
        padding-bottom: 1.5rem;
    }

    .leaderboard-title {
        font-size: 2.5rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
    }

    .leaderboard-title i {
        color: #f39c12;
        font-size: 3rem;
    }

    .live-indicator {
        color: #e74c3c;
        font-size: 1rem;
        margin-left: 0.5rem;
        animation: pulse 2s infinite;
    }

    .leaderboard-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1rem;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .participant-count {
        font-size: 1.1rem;
        color: #6c757d;
        font-weight: 500;
    }

    /* Top 3 Podium */
    .top-podium-section {
        margin-bottom: 3rem;
        padding: 2rem 0;
        background: linear-gradient(45deg, #f8f9fa, #e9ecef);
        border-radius: 15px;
    }

    .podium-container {
        display: flex;
        justify-content: center;
        align-items: end;
        gap: 2rem;
        flex-wrap: wrap;
        max-width: 1000px;
        margin: 0 auto;
    }

    .podium-winner {
        text-align: center;
        background: white;
        border-radius: 15px;
        padding: 2rem 1.5rem;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        min-width: 280px;
        position: relative;
    }

    .podium-winner:hover {
        transform: translateY(-5px);
    }

    .podium-1 {
        order: 2;
        transform: scale(1.05);
        border: 3px solid #ffd700;
    }

    .podium-2 {
        order: 1;
        border: 3px solid #c0c0c0;
    }

    .podium-3 {
        order: 3;
        border: 3px solid #cd7f32;
    }

    .winner-rank i {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }

    .podium-1 .winner-rank i { color: #ffd700; }
    .podium-2 .winner-rank i { color: #c0c0c0; }
    .podium-3 .winner-rank i { color: #cd7f32; }

    .winner-avatar {
        width: 80px;
        height: 80px;
        margin: 0 auto 1rem;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid #fff;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .winner-avatar img,
    .avatar-placeholder-winner {
        width: 100%;
        height: 100%;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2rem;
    }

    .winner-name {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.5rem;
    }

    .winner-score {
        font-size: 1.8rem;
        font-weight: 800;
        color: #e74c3c;
        margin-bottom: 0.3rem;
    }

    .winner-location {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 1rem;
    }

    .winner-stats {
        display: flex;
        justify-content: space-around;
        gap: 0.5rem;
        flex-wrap: wrap;
    }

    .stat-badge {
        background: #f8f9fa;
        padding: 0.5rem 0.8rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        color: #495057;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .stat-badge.coins { background-color: #fff3cd; color: #856404; }
    .stat-badge.reports { background-color: #cce5ff; color: #004085; }
    .stat-badge.waste { background-color: #d4edda; color: #155724; }

    /* Leaderboard Table */
    .leaderboard-table-wrapper {
        background: white;
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    }

    .leaderboard-table {
        margin: 0;
        font-size: 0.95rem;
    }

    .leaderboard-table thead th {
        background: linear-gradient(135deg, #2c3e50, #34495e);
        color: white;
        font-weight: 600;
        text-align: center;
        border: none;
        padding: 1.2rem 1rem;
        font-size: 0.9rem;
    }

    .participant-row {
        border-bottom: 1px solid #e9ecef;
        transition: background-color 0.2s ease;
    }

    .participant-row:hover {
        background-color: #f8f9fa;
    }

    .top-performer {
        background: linear-gradient(90deg, rgba(255,215,0,0.1), rgba(255,255,255,0.1));
    }

    .rank-cell {
        text-align: center;
        padding: 1rem;
    }

    .rank-display {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .medal-rank i.gold { color: #ffd700; }
    .medal-rank i.silver { color: #c0c0c0; }
    .medal-rank i.bronze { color: #cd7f32; }

    .rank-number {
        font-weight: 700;
        font-size: 1.1rem;
    }

    .participant-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 0.5rem 0;
    }

    .participant-avatar {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
    }

    .participant-avatar img,
    .avatar-placeholder {
        width: 100%;
        height: 100%;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .participant-name {
        font-weight: 600;
        color: #2c3e50;
        font-size: 1rem;
    }

    .participant-location {
        color: #6c757d;
        font-size: 0.85rem;
    }

    .coin-display {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        justify-content: center;
    }

    .coin-icon {
        color: #f39c12;
    }

    .coin-amount {
        font-weight: 600;
        color: #2c3e50;
    }

    .score-display {
        text-align: center;
    }

    .score-number {
        font-weight: 700;
        color: #e74c3c;
        font-size: 1.1rem;
    }

    .score-label {
        color: #6c757d;
        font-size: 0.8rem;
    }

    .status-badge {
        padding: 0.4rem 0.8rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .status-badge.eco-warrior {
        background-color: #d4edda;
        color: #155724;
    }

    .status-badge.collector-pro {
        background-color: #cce5ff;
        color: #004085;
    }

    .status-badge.community-champion {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-badge.participant {
        background-color: #e2e3e5;
        color: #495057;
    }

    /* Achievements Section */
    .achievements-main-section {
        background: white;
        padding: 3rem 0;
        margin-bottom: 0;
    }

    .achievements-header {
        text-align: center;
        margin-bottom: 3rem;
    }

    .achievements-title {
        font-size: 2.2rem;
        font-weight: 700;
        color: #2c3e50;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 1rem;
        margin-bottom: 0.5rem;
    }

    .achievements-title i {
        color: #f39c12;
    }

    .achievement-badge-count {
        color: #6c757d;
        font-size: 1rem;
        font-weight: 500;
    }

    .achievements-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1rem;
    }

    .achievement-item {
        background: white;
        border-radius: 15px;
        padding: 2rem;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        border-left: 4px solid #f39c12;
    }

    .achievement-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 40px rgba(0,0,0,0.15);
    }

    .achievement-content {
        display: flex;
        gap: 1.5rem;
        align-items: flex-start;
    }

    .achievement-icon-wrapper {
        flex-shrink: 0;
    }

    .achievement-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
        margin-bottom: 1rem;
    }

    .achievement-name {
        font-size: 1.3rem;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 0.8rem;
    }

    .achievement-desc {
        color: #6c757d;
        font-size: 1rem;
        margin-bottom: 1.5rem;
        line-height: 1.5;
    }

    .achievement-user-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 12px;
    }

    .achievement-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        overflow: hidden;
        flex-shrink: 0;
    }

    .achievement-avatar img,
    .avatar-placeholder-small {
        width: 100%;
        height: 100%;
        object-fit: cover;
        background: linear-gradient(135deg, #667eea, #764ba2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
    }

    .achievement-user-details {
        flex: 1;
    }

    .achievement-username {
        font-weight: 600;
        color: #2c3e50;
        font-size: 1rem;
        display: block;
        margin-bottom: 0.2rem;
    }

    .achievement-timestamp {
        color: #6c757d;
        font-size: 0.85rem;
    }

    .achievement-metrics {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .metric {
        background: #e9ecef;
        padding: 0.5rem 0.8rem;
        border-radius: 15px;
        font-size: 0.85rem;
        color: #495057;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.3rem;
    }

    .no-achievements-state {
        text-align: center;
        padding: 4rem 2rem;
        background: #f8f9fa;
        border-radius: 15px;
        margin: 0 1rem;
    }

    .no-achievements-content {
        max-width: 400px;
        margin: 0 auto;
    }

    .no-achievement-icon {
        font-size: 4rem;
        color: #dee2e6;
        margin-bottom: 1.5rem;
    }

    .no-achievements-content h3 {
        color: #6c757d;
        margin-bottom: 1rem;
        font-size: 1.5rem;
    }

    .no-achievements-content p {
        color: #6c757d;
        margin-bottom: 2rem;
    }

    /* Quick Stats Footer */
    .quick-stats-footer {
        background: linear-gradient(135deg, #2c3e50, #34495e);
        color: white;
        padding: 2rem 0;
    }

    .stats-summary {
        display: flex;
        justify-content: space-around;
        align-items: center;
        flex-wrap: wrap;
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .stat-item {
        text-align: center;
        min-width: 150px;
    }

    .stat-number {
        display: block;
        font-size: 2rem;
        font-weight: 700;
        color: #f39c12;
        margin-bottom: 0.5rem;
    }

    .stat-label {
        font-size: 1rem;
        color: #000000ff;
        font-weight: 500;
    }

    .update-info {
        display: flex;
        align-items: center;
        gap: 1rem;
        color: #bdc3c7;
        font-size: 0.9rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .mini-refresh-btn {
        background: none;
        border: 2px solid #34495e;
        color: #ecf0f1;
        padding: 0.5rem 1rem;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .mini-refresh-btn:hover {
        background: #34495e;
        border-color: #f39c12;
        color: #f39c12;
    }

    /* Refresh Button */
    .refresh-btn {
        background: rgba(255,255,255,0.2);
        border: 2px solid rgba(255,255,255,0.3);
        color: white;
        padding: 0.6rem 1.2rem;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
    }

    .refresh-btn:hover {
        background: rgba(255,255,255,0.3);
        border-color: rgba(255,255,255,0.5);
    }

    /* Animations */
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }

    .pulsing {
        animation: pulse 2s infinite;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .leaderboard-main-section {
            padding: 2rem 0;
        }

        .leaderboard-card {
            padding: 1.5rem;
            margin: 0 1rem;
        }

        .leaderboard-title {
            font-size: 1.8rem;
            flex-direction: column;
            gap: 0.5rem;
        }

        .leaderboard-title i {
            font-size: 2rem;
        }

        .podium-container {
            flex-direction: column;
            align-items: center;
            gap: 1rem;
        }

        .podium-winner {
            min-width: 250px;
            margin-bottom: 1rem;
            padding: 1.5rem;
        }

        .podium-1 {
            order: 1;
            transform: scale(1);
        }

        .achievements-grid {
            grid-template-columns: 1fr;
            gap: 1.5rem;
            padding: 0 1rem;
        }

        .achievement-content {
            flex-direction: column;
            text-align: center;
        }

        .achievement-icon-wrapper {
            align-self: center;
        }

        .stats-summary {
            flex-direction: column;
            text-align: center;
        }

        .stat-item {
            margin-bottom: 1rem;
        }

        .update-info {
            flex-direction: column;
            gap: 0.5rem;
        }

        .leaderboard-meta {
            flex-direction: column;
            text-align: center;
            gap: 0.5rem;
        }

        .participant-info {
            flex-direction: column;
            text-align: center;
            gap: 0.5rem;
        }

        .leaderboard-table {
            font-size: 0.8rem;
        }

        .leaderboard-table th,
        .leaderboard-table td {
            padding: 0.5rem 0.3rem;
        }

        .achievement-user-info {
            flex-direction: column;
            text-align: center;
            gap: 0.8rem;
        }

        .achievement-metrics {
            justify-content: center;
        }

        .achievements-title {
            font-size: 1.8rem;
        }

        .no-achievements-state {
            padding: 2rem 1rem;
            margin: 0 1rem;
        }
    }
    </style>

    <script src="{{ asset('js/appbar.js') }}"></script>
</body>

</html>
