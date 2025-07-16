<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Waste2Worth - Report Waste</title>
    <link rel="shortcut icon" href="/frontend/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="css/reportWaste.css">
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
                        <li><i class="fas fa-home"></i><a href="/frontend/dashboard.html">Home</a></li>
                        <li><i class="fas fa-user"></i><a href="/frontend/profile.html">Profile</a></li>
                        <li><i class="fas fa-heart"></i><a href="/frontend/volunteer.html">Volunteer</a></li>
                    </ul>
                </div>
                <div class="nav-section">
                    <h2>MAIN</h2>
                    <ul>
                        <li><i class="fas fa-users"></i><a href="/frontend/community.html">Community</a></li>
                        <li><i class="fas fa-users"></i><a href="/frontend/reportWaste.html">Waste Report</a></li>
                        <li><i class="fas fa-users"></i><a href="/frontend/reward_system.html">Reward</a></li>
                    </ul>
                </div>
                <div class="nav-section">
                    <h2>SUPPORT</h2>
                    <ul>
                        <li><i class="fas fa-flag"></i><a href="/frontend/reporting.html">Report</a></li>
                        <li><i class="fas fa-circle-question"></i><a href="/frontend/help.html">Help</a></li>
                    </ul>
                </div>
            </nav>
        </aside>

        <main class="main-content">
            <h2>Report Waste</h2>

            <section class="card">
                <h3>Submit a Waste Report</h3>
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

    <script src="js/reportWaste.js"></script>
</body>

</html>
