<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Waste2Worth - Report Waste</title>
    <link rel="shortcut icon" href="/frontend/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/reportWaste.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="layout">
        <!-- Sidebar -->
<<<<<<< HEAD
        @include('components.sidebar')
=======
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('frontend/image/logo.png') }}" alt="Logo" class="sidebar-logo">
                <span class="sidebar-title">Waste2Worth</span>
            </div>
            <div class="sidebar-user">
                <img src="{{ asset('frontend/image/user.jpg') }}" alt="User" class="user-avatar">
                <span class="user-name">Hi, User!</span>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <h3>Account</h3>
                    <ul>
                        <li><a href="{{ url('/home') }}"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="#"><i class="fas fa-user"></i> Profile</a></li>
                        <li><a href="#"><i class="fas fa-heart"></i> Volunteer</a></li>
                    </ul>
                </div>
                <div class="nav-section">
                    <h3>Main</h3>
                    <ul>
                        <li><a href="{{ url('/community') }}"><i class="fas fa-users"></i> Community</a></li>
                        <li><a href="{{ url('/reportWaste') }}"><i class="fas fa-dumpster"></i> Waste Report</a></li>
                        <li><a href="#"><i class="fas fa-gift"></i> Reward</a></li>
                    </ul>
                </div>
                <div class="nav-section">
                    <h3>Support</h3>
                    <ul>
                        <li><a href="#"><i class="fas fa-flag"></i> Report</a></li>
                        <li><a href="{{ url('/help') }}"><i class="fas fa-circle-question"></i> Help</a></li>
                        <li><a href="{{ url('/contact') }}"><i class="fas fa-envelope"></i> Contact Us</a></li>
                    </ul>
                </div>
            </nav>
        </aside>
>>>>>>> af41adae68b7d627f9863119a5941aa2b8eef0ef

        <!-- Main Content -->
        <main class="main-content">
            <section class="card">
                <h2>Report Waste</h2>
                <form id="wasteForm">
                    <label>Waste Type
                        <select id="wasteType">
                            <option>Plastic</option>
                            <option>Paper</option>
                            <option>Glass</option>
                            <option>Metal</option>
                            <option>Organic</option>
                            <option>Electronic</option>
                            <option>Other</option>
                        </select>
                    </label>
                    <label>Estimated Amount
                        <div class="input-group">
                            <input type="number" id="wasteAmount" placeholder="Amount" required>
                            <select id="wasteUnit">
                                <option>kg</option>
                                <option>lbs</option>
                            </select>
                        </div>
                    </label>
                    <label>Location
                        <input type="text" id="wasteLocation" placeholder="Location" required>
                    </label>
                    <label>Description
                        <textarea id="wasteDescription" rows="3" placeholder="Details..."></textarea>
                    </label>
                    <label>Upload Photo
                        <input type="file" id="wasteImage" accept="image/*">
                    </label>
                    <button type="submit">Submit Report</button>
                </form>
            </section>
            <section class="card">
                <h3>Recent Waste Reports</h3>
                <div id="recentReports"></div>
            </section>
        </main>

        <!-- Stats Bar -->
        <aside class="stats-bar">
            <h2>Waste Statistics</h2>
            <div class="stat">
                <h3>Total Waste Reported</h3>
                <p id="totalWaste">0 kg</p>
                <p class="muted">All time</p>
            </div>
            <div class="stat">
                <h3>Most Reported Waste Type</h3>
                <p id="mostType">None</p>
            </div>
            <div class="stat">
                <h3>Cleanup Progress</h3>
                <div class="progress-bar">
                    <div id="cleanupBar"></div>
                </div>
                <p id="cleanupText" class="muted">0% of reported waste cleaned</p>
            </div>
        </aside>
    </div>
    <script src="{{ asset('js/reportWaste.js') }}"></script>
</body>

</html>
