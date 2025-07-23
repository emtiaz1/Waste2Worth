<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Leaderboard | Waste2Worth</title>
    <link rel="stylesheet" href="{{ asset('css/leaderboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="layout">
        @include('components.sidebar')
        <main class="main-content">
            <section class="leaderboard-card">
                <h2 class="leaderboard-title">Leaderboard</h2>
                <div class="top-contributors-section">
                    <h3 class="section-heading"><i class="fas fa-trophy"></i> Top Contributors</h3>
                    <table class="leaderboard-table">
                        <thead>
                            <tr>
                                <th>Rank</th>
                                <th>User</th>
                                <th>Contributions</th>
                                <th>Score</th>
                                <th>EcoTokens</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="medal gold"><i class="fas fa-medal"></i></span> 1</td>
                                <td>EcoWarrior123</td>
                                <td>152</td>
                                <td>980</td>
                                <td>3,750</td>
                            </tr>
                            <tr>
                                <td><span class="medal silver"><i class="fas fa-medal"></i></span> 2</td>
                                <td>GreenHero</td>
                                <td>138</td>
                                <td>870</td>
                                <td>3,420</td>
                            </tr>
                            <tr>
                                <td><span class="medal bronze"><i class="fas fa-medal"></i></span> 3</td>
                                <td>EarthGuardian</td>
                                <td>125</td>
                                <td>790</td>
                                <td>3,100</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="achievements-section">
                    <h3 class="section-heading">Recent Achievements</h3>
                    <div class="achievements-list">
                        <div class="achievement-card">
                            <img src="/frontend/image/user1.jpg" alt="User" class="achievement-avatar">
                            <div>
                                <div class="achievement-title">Waste Warrior</div>
                                <div class="achievement-desc">Collected 100kg of waste</div>
                                <div class="achievement-earned">Earned by: <span class="user-link">EcoWarrior123</span></div>
                            </div>
                        </div>
                        <div class="achievement-card">
                            <img src="/frontend/image/user2.jpg" alt="User" class="achievement-avatar">
                            <div>
                                <div class="achievement-title">Community Leader</div>
                                <div class="achievement-desc">Organized 10 clean-up events</div>
                                <div class="achievement-earned">Earned by: <span class="user-link">GreenHero</span></div>
                            </div>
                        </div>
                        <div class="achievement-card">
                            <img src="/frontend/image/user3.jpg" alt="User" class="achievement-avatar">
                            <div>
                                <div class="achievement-title">Eco Innovator</div>
                                <div class="achievement-desc">Proposed 5 sustainable solutions</div>
                                <div class="achievement-earned">Earned by: <span class="user-link">EarthGuardian</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <script src="{{ asset('js/leaderboard.js') }}"></script>
</body>
</html>