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

</div>
</body>

</html>

<?php
Route::get('/reportWaste', function () {
    return view('reportWaste');
});
?>