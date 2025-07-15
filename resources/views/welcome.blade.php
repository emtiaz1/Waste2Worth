<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Waste2Worth</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>

<body>
    <!-- Header -->
    <header class="header">
        <div class="header-left">
            <img src="{{ asset('frontend/image/logo.png') }}" alt="Logo" class="logo">
            <h2>Waste2Worth</h1>
        </div>
        <div class="header-right">
            <span>Hi, User!</span>
        </div>
    </header>

    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <nav>
                <div class="section">
                    <h2>ACCOUNT</h2>
                    <ul>
                        <li><a href="/frontend/leaderboard.html">🏠 Leaderboard</a></li>
                        <li><a href="/frontend/profile.html">👤 Profile</a></li>
                        <li><a href="{{ route('register') }}">❤️ Volunteer</a></li>
                    </ul>
                </div>

                <div class="section">
                    <h2>MAIN</h2>
                    <ul>
                        <li><a href="{{ route('event') }}"> Event</a></li>
                        <li><a href="{{ route('community') }}">👥 Community</a></li>
                        <li><a href="/frontend/report_waste.html">🗑️ Waste Report</a></li>
                        <li><a href="/frontend/reward_system.html">🏆 Reward</a></li>
                    </ul>
                </div>

                <div class="section">
                    <h2>SUPPORT</h2>
                    <ul>
                        <li><a href="/frontend/reporting.html">🚩 Report</a></li>
                        <li><a href="{{ route('help') }}">❓ Help</a></li>
                    </ul>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <section class="section-box">
                <h2>Ongoing Events</h2>
                <div class="event-grid">
                    <div class="event">
                        <img src="{{ asset('frontend/image/dashboard1.png') }}" alt="Event 1">
                        <div class="date-badge">20-June-2025</div>
                    </div>
                    <div class="event">
                        <img src="{{ asset('frontend/image/dashboard2.png.jpg') }}" alt="Event 2">
                        <div class="date-badge">25-June-2025</div>
                    </div>
                    <div class="event">
                        <img src="{{ asset('frontend/image/dashboard3.jpg') }}" alt="Event 3">
                        <div class="date-badge">15-July-2025</div>
                    </div>
                </div>
            </section>

            <section class="section-box">
                <div class="report-header">
                    <div>
                        <h2>Found Waste Report?</h2>
                        <p>Reporting waste</p>
                    </div>
                    <a href="/frontend/report.html" class="btn-report">REPORT WASTE</a>
                </div>
            </section>
        <section class="section-box">
            <h2>Our Communities</h2>
            <div class="community-grid">
                @for ($i = 1; $i <= 6; $i++)
                    <div class="community-card">
                        <img src="{{ asset('frontend/image/communities' . $i . '.jpg') }}" alt="Community {{ $i }}">
                    </div>
                @endfor
            </div>
        </section>

        </main>

        <!-- News Sidebar -->
        <aside class="news-sidebar">
            <h2>News Archive</h2>
            <div id="newsContainer">
                <div class="news-card">
                    <img src="{{ asset('frontend/image/communities7.jpg') }}" alt="News">
                    <div class="news-content">
                        <h3>Recycle Games</h3>
                        <p>Let's reduce plastic waste using games and.....</p>
                        <button onclick="showMoreNews()">View More</button>
                    </div>
                </div>
            </div>
        </aside>
    </div>


    <script src="{{ asset('js/script.js') }}"></script>
</body>

</html>
