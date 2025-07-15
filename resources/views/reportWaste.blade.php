<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Zero Waste - Report Waste</title>
    <link rel="shortcut icon" href="/frontend/logo.png" type="image/x-icon">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50">
    <!-- Header -->
    <header class="flex items-center justify-between px-4 py-2 bg-white border-b">
        <div class="flex items-center gap-2">
            <img src="{{ asset('frontend/image/logo.png') }}" alt="Logo" class="logo" height="50" width="50">
            <h1 class="text-xl font-semibold">ZeroWaste</h1>
        </div>
        <div class="flex items-center gap-4">
            <span>Hi, User!</span>
        </div>
    </header>

    <div class="flex">
        <!-- Sidebar -->
        <aside class="w-64 min-h-screen bg-white border-r">
            <nav class="p-4">
                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-gray-500 mb-2">ACCOUNT</h2>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-3 px-3 py-2 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-home"></i>
                            <a href="/frontend/dashboard.html">Home</a>
                        </li>
                        <li class="flex items-center gap-3 px-3 py-2 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-user"></i>
                            <a href="/frontend/profile.html">Profile</a>
                        </li>
                        <li class="flex items-center gap-3 px-3 py-2 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-heart"></i>
                            <a href="/frontend/volunteer.html">Volunteer</a>
                        </li>
                    </ul>
                </div>

                <div class="mb-8">
                    <h2 class="text-sm font-semibold text-gray-500 mb-2">MAIN</h2>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-3 px-3 py-2 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-users"></i>
                            <a href="/frontend/community.html">Community</a>
                        </li>
                        <li class="flex items-center gap-3 px-3 py-2 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-users"></i>
                            <a href="/frontend/reportWaste.html">Waste Report</a>
                        </li>
                        <li class="flex items-center gap-3 px-3 py-2 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-users"></i>
                            <a href="/frontend/reward_system.html">Reward</a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h2 class="text-sm font-semibold text-gray-500 mb-2">SUPPORT</h2>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-3 px-3 py-2 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-flag"></i>
                            <a href="/frontend/reporting.html">Report</a>
                        </li>
                        <li class="flex items-center gap-3 px-3 py-2 hover:bg-gray-100 rounded-lg">
                            <i class="fas fa-circle-question"></i>
                            <a href="/frontend/help.html">Help</a>
                        </li>
                    </ul>
                </div>

            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6 min-h-screen flex flex-col gap-8">
            <h2 class="text-2xl font-semibold">Report Waste</h2>

            <!-- Waste Reporting Form -->
            <section class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold mb-4">Submit a Waste Report</h3>
                <form id="wasteForm" class="space-y-4">
                    <div>
                        <label class="block mb-1 font-medium">Waste Type</label>
                        <select id="wasteType" class="w-full px-3 py-2 border rounded-md">
                            <option>Plastic</option>
                            <option>Paper</option>
                            <option>Glass</option>
                            <option>Metal</option>
                            <option>Organic</option>
                            <option>Electronic</option>
                            <option>Other</option>
                        </select>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Estimated Amount</label>
                        <div class="flex">
                            <input type="number" id="wasteAmount" class="w-full px-3 py-2 border rounded-l-md"
                                placeholder="Amount" required>
                            <select id="wasteUnit" class="border border-l-0 rounded-r-md px-3 py-2">
                                <option>kg</option>
                                <option>lbs</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Location</label>
                        <input type="text" id="wasteLocation" class="w-full px-3 py-2 border rounded-md"
                            placeholder="Location" required>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Description</label>
                        <textarea id="wasteDescription" rows="3" class="w-full px-3 py-2 border rounded-md"
                            placeholder="Details..."></textarea>
                    </div>
                    <div>
                        <label class="block mb-1 font-medium">Upload Photo</label>
                        <input type="file" id="wasteImage" accept="image/*" class="w-full px-3 py-2 border rounded-md">
                    </div>
                    <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600">Submit
                        Report</button>
                </form>
            </section>

            <!-- Recent Waste Reports -->
            <section class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-semibold mb-4">Recent Waste Reports</h3>
                <div id="recentReports" class="space-y-4">
                    <!-- New reports will appear here -->
                </div>
            </section>
        </main>

        <!-- Waste Statistics -->
        <aside class="w-80 p-6 border-l min-h-screen bg-white space-y-6">
            <h2 class="text-2xl font-semibold">Waste Statistics</h2>
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="font-semibold mb-2">Total Waste Reported</h3>
                <p id="totalWaste" class="text-3xl font-bold text-green-600">0 kg</p>
                <p class="text-sm text-gray-500">All time</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="font-semibold mb-2">Most Reported Waste Type</h3>
                <p id="mostType" class="text-xl font-bold">None</p>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <h3 class="font-semibold mb-2">Cleanup Progress</h3>
                <div class="w-full bg-gray-200 rounded-full h-2.5">
                    <div id="cleanupBar" class="bg-green-600 h-2.5 rounded-full" style="width: 0%"></div>
                </div>
                <p id="cleanupText" class="text-sm text-gray-500 mt-2">0% of reported waste cleaned</p>
            </div>
        </aside>
    </div>

    
</body>

</html>

<?php
Route::get('/reportWaste', function () {
    return view('reportWaste');
});
?>