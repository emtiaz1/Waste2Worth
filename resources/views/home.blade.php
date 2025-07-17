<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Waste2Worth - Home</title>
    <link rel="shortcut icon" href="/frontend/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header class="header">
        <div class="header-left">
            <img src="{{ asset('frontend/image/logo.png') }}" alt="Logo" class="logo">
            <h1>Waste2Worth</h1>
        </div>
        <div class="header-right">
            <span>Hi, User!</span>
        </div>
    </header>

    <div class="container">
        <aside class="sidebar">
            <nav>
                <div class="nav-section">
                    <h2>ACCOUNT</h2>
                    <ul>
                        <li><i class="fas fa-home"></i><a href="{{ url('/home') }}">Home</a></li>
                        <li><i class="fas fa-user"></i><a href="#">Profile</a></li>
                        <li><i class="fas fa-heart"></i><a href="#">Volunteer</a></li>
                    </ul>
                </div>
                <div class="nav-section">
                    <h2>MAIN</h2>
                    <ul>
                        <li><i class="fas fa-users"></i><a href="{{ url('/community') }}">Community</a></li>
                        <li><i class="fas fa-dumpster"></i><a href="{{ url('/reportWaste') }}">Waste Report</a></li>
                        <li><i class="fas fa-gift"></i><a href="#">Reward</a></li>
                    </ul>
                </div>
                <div class="nav-section">
                    <h2>SUPPORT</h2>
                    <ul>
                        <li><i class="fas fa-flag"></i><a href="#">Report</a></li>
                        <li><i class="fas fa-circle-question"></i><a href="{{ url('/help') }}">Help</a></li>
                    </ul>
                </div>
            </nav>
        </aside>

        <main class="main-content">
            <section class="card welcome-card">
                <h2>Welcome Back!</h2>
                <p>Thank you for making a difference with Waste2Worth. Here’s what you can do next:</p>
                <div class="card-grid">
                    <div class="card action-card">
                        <img src="{{ asset('frontend/image/cleanup.jpg') }}" alt="Cleanup" class="card-img">
                        <h3>Join a Clean-up</h3>
                        <p>Participate in local clean-up events and help restore your community.</p>
                        <a href="{{ url('/event') }}" class="btn">View Events</a>
                    </div>
                    <div class="card action-card">
                        <img src="{{ asset('frontend/image/report.jpg') }}" alt="Report Waste" class="card-img">
                        <h3>Report Waste</h3>
                        <p>Spot illegal dumping or pollution? Report it and help keep our planet clean.</p>
                        <a href="{{ url('/reportWaste') }}" class="btn">Report Now</a>
                    </div>
                    <div class="card action-card">
                        <img src="{{ asset('frontend/image/community.jpg') }}" alt="Community" class="card-img">
                        <h3>Connect with Community</h3>
                        <p>Share your impact, join discussions, and inspire others to take action.</p>
                        <a href="{{ url('/community') }}" class="btn">Join Community</a>
                    </div>
                </div>
            </section>
            <section class="card impact-card">
                <h3>Your Impact</h3>
                <div class="stat">
                    <h4>Total Waste Reported</h4>
                    <p id="totalWaste">0 kg</p>
                    <p class="muted">All time</p>
                </div>
                <div class="stat">
                    <h4>Most Reported Waste Type</h4>
                    <p id="mostType">None</p>
                </div>
                <div class="progress-bar">
                    <div id="cleanupBar" style="width:0%"></div>
                </div>
                <p id="cleanupText">0% of reported waste cleaned</p>
            </section>
        </main>
    </div>
    <script src="{{ asset('js/reportWaste.js') }}"></script>
</body>
</html>