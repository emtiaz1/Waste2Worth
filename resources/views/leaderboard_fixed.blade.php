<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Leaderboard | Waste2Worth</title>
    <link rel="stylesheet" href="{{ asset('css/appbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/leaderboard.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    @include('layouts.appbar')
    <div class="layout" id="mainLayout">
        <div class="main-content">
            <main class="main-content">
                <section class="leaderboard-card">
                    <div class="leaderboard-header">
                        <h2 class="leaderboard-title">
                            <i class="fas fa-trophy"></i> Community Leaderboard
                            <span class="live-indicator">●</span>
                        </h2>
                        <div class="leaderboard-stats">
                            <div class="stat-item">
                                <span class="stat-value" id="totalParticipants">{{ $leaderboardData->count() }}</span>
                                <span class="stat-label">Participants</span>
                            </div>
                            <div class="stat-item">
                                <span class="stat-value" id="totalCoins">0</span>
                                <span class="stat-label">EcoCoins Distributed</span>
                            </div>
                        </div>
                    </div>

                    <div class="top-contributors-section">
                        <h3 class="section-heading"><i class="fas fa-medal"></i> Top Contributors</h3>
                        
                        @if($leaderboardData->count() >= 3)
                        <div class="podium-section">
                            @foreach($leaderboardData->take(3) as $index => $user)
                            <div class="podium-item podium-{{ $index + 1 }}">
                                <div class="podium-avatar">
                                    @if($user->profile_picture)
                                        <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->display_name }}">
                                    @else
                                        <div class="avatar-placeholder">
                                            <i class="fas fa-user"></i>
                                        </div>
                                    @endif
                                    <div class="podium-badge">
                                        <i class="{{ $user->badge['icon'] }}" style="color: {{ $user->badge['color'] }}"></i>
                                    </div>
                                </div>
                                <h4>{{ $user->display_name }}</h4>
                                <p class="podium-score">{{ number_format($user->performance_score, 0) }} pts</p>
                                <p class="podium-coins">{{ number_format($user->eco_coins) }} EcoCoins</p>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <table class="leaderboard-table">
                            <thead>
                                <tr>
                                    <th>Rank</th>
                                    <th>User</th>
                                    <th>Reports</th>
                                    <th>Waste (kg)</th>
                                    <th>Performance Score</th>
                                    <th>EcoCoins</th>
                                    <th>Badge</th>
                                </tr>
                            </thead>
                            <tbody id="leaderboardTableBody">
                                @foreach($leaderboardData as $user)
                                <tr class="leaderboard-row" data-rank="{{ $user->rank }}">
                                    <td>
                                        <span class="rank-number rank-{{ $user->rank <= 3 ? $user->rank : 'other' }}">
                                            @if($user->rank <= 3)
                                                <i class="{{ $user->badge['icon'] }}" style="color: {{ $user->badge['color'] }}"></i>
                                            @endif
                                            {{ $user->rank }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="user-info">
                                            <div class="user-avatar">
                                                @if($user->profile_picture)
                                                    <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="{{ $user->display_name }}">
                                                @else
                                                    <div class="avatar-placeholder-small">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="user-details">
                                                <span class="username">{{ $user->display_name }}</span>
                                                <small class="location">{{ $user->location ?: 'Unknown Location' }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->reports_count }}</td>
                                    <td>{{ number_format($user->total_waste, 1) }}</td>
                                    <td>
                                        <span class="performance-score">{{ number_format($user->performance_score, 0) }}</span>
                                    </td>
                                    <td>
                                        <span class="eco-coins">{{ number_format($user->eco_coins) }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $user->badge['type'] }}" title="{{ $user->badge['title'] }}">
                                            <i class="{{ $user->badge['icon'] }}" style="color: {{ $user->badge['color'] }}"></i>
                                            {{ $user->badge['title'] }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        @if($leaderboardData->count() == 0)
                        <div class="no-data">
                            <i class="fas fa-users"></i>
                            <h3>No participants yet</h3>
                            <p>Be the first to contribute and appear on the leaderboard!</p>
                        </div>
                        @endif
                    </div>

                    <div class="achievements-section">
                        <h3 class="section-heading">
                            <i class="fas fa-trophy"></i> Recent Achievements
                            <span class="achievement-count">{{ count($recentAchievements ?? []) }} this week</span>
                        </h3>
                        
                        @if(isset($recentAchievements) && count($recentAchievements) > 0)
                        <div class="achievements-grid">
                            @foreach($recentAchievements as $achievement)
                            <div class="achievement-card achievement-{{ $achievement['type'] }}">
                                <div class="achievement-header">
                                    <div class="achievement-icon" style="background-color: {{ $achievement['color'] }}20; color: {{ $achievement['color'] }}">
                                        <i class="{{ $achievement['icon'] }}"></i>
                                    </div>
                                    <div class="achievement-meta">
                                        <h4 class="achievement-title">{{ $achievement['title'] }}</h4>
                                        <p class="achievement-description">{{ $achievement['description'] }}</p>
                                    </div>
                                </div>
                                
                                <div class="achievement-user">
                                    <div class="user-avatar-small">
                                        @if($achievement['user']['avatar'])
                                            <img src="{{ asset('storage/' . $achievement['user']['avatar']) }}" alt="{{ $achievement['user']['name'] }}">
                                        @else
                                            <div class="avatar-placeholder-mini">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="user-info-mini">
                                        <span class="username-mini">{{ $achievement['user']['name'] }}</span>
                                        <small class="location-mini">{{ $achievement['user']['location'] ?: 'Unknown Location' }}</small>
                                    </div>
                                    <div class="achievement-timestamp">
                                        <small>{{ $achievement['timestamp']->diffForHumans() }}</small>
                                    </div>
                                </div>

                                <div class="achievement-stats">
                                    @if(isset($achievement['stats']['reports']))
                                        <div class="stat-mini">
                                            <i class="fas fa-file-alt"></i>
                                            <span>{{ $achievement['stats']['reports'] }} reports</span>
                                        </div>
                                    @endif
                                    @if(isset($achievement['stats']['coins']))
                                        <div class="stat-mini">
                                            <i class="fas fa-coins"></i>
                                            <span>{{ number_format($achievement['stats']['coins']) }} coins</span>
                                        </div>
                                    @endif
                                    @if(isset($achievement['stats']['waste_amount']))
                                        <div class="stat-mini">
                                            <i class="fas fa-weight-hanging"></i>
                                            <span>{{ number_format($achievement['stats']['waste_amount'], 1) }}kg</span>
                                        </div>
                                    @endif
                                    @if(isset($achievement['stats']['active_days']))
                                        <div class="stat-mini">
                                            <i class="fas fa-calendar"></i>
                                            <span>{{ $achievement['stats']['active_days'] }} days</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <div class="no-achievements">
                            <i class="fas fa-trophy"></i>
                            <h4>No recent achievements</h4>
                            <p>Start contributing to earn your first achievement!</p>
                        </div>
                        @endif
                    </div>

                    <div class="leaderboard-footer">
                        <p class="last-update">Last updated: <span id="lastUpdate">{{ now()->format('M d, Y h:i A') }}</span></p>
                        <button class="refresh-btn" onclick="refreshLeaderboard()">
                            <i class="fas fa-sync-alt"></i> Refresh
                        </button>
                    </div>
                </section>
            </main>
        </div>
    </div>

    <script src="{{ asset('js/leaderboard.js') }}"></script>
    <script src="{{ asset('js/appbar.js') }}"></script>
</body>

</html>
