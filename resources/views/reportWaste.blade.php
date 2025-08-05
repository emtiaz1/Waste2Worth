<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Waste2Worth - Report Waste</title>
    <link rel="shortcut icon" href="/frontend/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/appbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reportWaste.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    @include('components.appbar')
    <div class="layout" id="mainLayout">
        <div class="main-content">
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
        <script src="{{ asset('js/appbar.js') }}"></script>
</body>

</html>