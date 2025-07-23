<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Waste2Worth - Home</title>
    <link rel="shortcut icon" href="/frontend/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        @include('components.sidebar')
        <!-- Main Content -->
        <main class="main-content">
            <section class="card welcome-card">
                <h2>Welcome Back!</h2>
                <p>Thank you for making a difference with Waste2Worth. Here’s what you can do next:</p>
                <div class="card-grid">
                    <div class="card action-card">
                        <img src="{{ asset('frontend/image/Cleanup4.jpg') }}" alt="Cleanup" class="card-img">
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
                        <img src="{{ asset('frontend/image/Communities.jpg') }}" alt="Community" class="card-img">
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