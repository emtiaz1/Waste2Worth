<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Waste2Worth</title>
    <link rel="shortcut icon" href="/frontend/logo.png" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <link rel="stylesheet" href="{{ asset('css/appbar.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" rel="stylesheet">
</head>

<body>
    @include('layouts.appbar')
    <div class="layout" id="mainLayout">
        <main class="main-content">
            <!-- Welcome Section with Profile Info -->
            @if($profile)
            <section class="card profile-overview-card">
                <div class="profile-overview">
                    <div class="profile-overview-left">
                        <div class="profile-picture-small">
                            @if($profile->profile_picture)
                                <img src="{{ asset($profile->profile_picture) }}?t={{ time() }}" 
                                     alt="Profile Picture" class="profile-img-small">
                            @else
                                <img src="{{ asset('frontend/image/dp.jpg') }}" 
                                     alt="Default Profile" class="profile-img-small">
                            @endif
                        </div>
                        <div class="profile-overview-info">
                            <h2>Welcome back, {{ $profile->display_name ?? 'User' }}!</h2>
                            <p class="profile-overview-status">{{ $profile->status ?? 'Ready to make a difference' }}</p>
                            <div class="profile-overview-stats">
                                <div class="overview-stat">
                                    <i class="fas fa-leaf"></i>
                                    <span>{{ $profile->contribution ?? 0 }} Contributions</span>
                                </div>
                                <div class="overview-stat">
                                    <i class="fas fa-coins"></i>
                                    <span>{{ $profile->total_token ?? 0 }} Tokens</span>
                                </div>
                                <div class="overview-stat">
                                    <i class="fas fa-recycle"></i>
                                    <span>{{ $profile->waste_reports_count ?? 0 }} Reports</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="profile-overview-right">
                        <a href="{{ route('profile.show') }}" class="btn btn-profile">
                            <i class="fas fa-user-edit"></i>
                            Edit Profile
                        </a>
                    </div>
                </div>
            </section>
            @endif

            <!-- Ongoing Events -->
            <section class="card welcome-card">
                <div>
                    <h2 class="card-title">Ongoing Events</h2>
                    <div class="card-grid">
                        <div class="card action-card">
                            <img src="{{ asset('frontend/image/Cleanup4.jpg') }}" alt="Event 1" class="card-img">
                            <div>
                                <h3>Community Cleanup</h3>
                                <p>Join our city-wide cleanup event and make a difference!</p>
                                <div class="muted mb-2">20-NOV-2025</div>
                            </div>
                        </div>
                        <div class="card action-card">
                            <img src="{{ asset('frontend/image/event2.jpg') }}" alt="Event 2" class="card-img">
                            <div>
                                <h3>Plastic Free Drive</h3>
                                <p>Help us reduce plastic waste in your neighborhood.</p>
                                <div class="muted mb-2">05-DEC-2025</div>
                            </div>
                        </div>
                        <div class="card action-card">
                            <img src="{{ asset('frontend/image/event3.jpg') }}" alt="Event 3" class="card-img">
                            <div>
                                <h3>Tree Plantation</h3>
                                <p>Plant trees and contribute to a greener tomorrow.</p>
                                <div class="muted mb-2">15-JAN-2026</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Waste Report -->
            <section class="card" style="height: 120px;">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h2 class="h4 mb-0">Found Waste Report?</h2>
                        <p class="text-muted mb-0">Reporting waste helps us keep the community clean.</p>
                    </div>
                    <a href="{{ url('/reportWaste') }}" class="btn">REPORT WASTE</a>
                </div>
            </section>
            <!-- Our Communities -->
            <section class="card" style="height:100%; margin-bottom: 0;">
                <h2 class="h4 mb-4">Our Communities</h2>
                <div class="scrolling-wrapper">
                    <div class="card rounded overflow-hidden" style="width: 250px; height: 200px;">
                        <img src="{{ asset('frontend/image/Communities.jpg') }}" alt="Community" class="card-img-top"
                            style="height: 180px; object-fit: cover;">
                    </div>
                    <div class="card rounded overflow-hidden" style="width: 250px; height: 200px;">
                        <img src="{{ asset('frontend/image/clean1.jpg') }}" alt="Community" class="card-img-top"
                            style="height: 180px; object-fit: cover;">
                    </div>
                    <div class="card rounded overflow-hidden" style="width: 250px; height: 200px;">
                        <img src="{{ asset('frontend/image/clean2.png') }}" alt="Community" class="card-img-top"
                            style="height: 180px; object-fit: cover;">
                    </div>
                    <div class="card rounded overflow-hidden" style="width: 250px; height: 200px;">
                        <img src="{{ asset('frontend/image/clean3.png') }}" alt="Community" class="card-img-top"
                            style="height: 180px; object-fit: cover;">
                    </div>
                    <div class="card rounded overflow-hidden" style="width: 250px; height: 200px;">
                        <img src="{{ asset('frontend/image/clean4.jpg') }}" alt="Community" class="card-img-top"
                            style="height: 180px; object-fit: cover;">
                    </div>
                    <div class="card rounded overflow-hidden" style="width: 250px; height: 200px;">
                        <img src="{{ asset('frontend/image/clean5.png') }}" alt="Community" class="card-img-top"
                            style="height: 180px; object-fit: cover;">
                    </div>
                </div>
            </section>
            <!-- Impact Section -->
            <section class="card impact-card">
                <h3 class="card-title">Your Impact</h3>
                <div class="stat mb-3">
                    <h4>Total Waste Reported</h4>
                    <p id="totalWaste">0 kg</p>
                    <p class="muted">All time</p>
                </div>
                <div class="stat mb-3">
                    <h4>Most Reported Waste Type</h4>
                    <p id="mostType">None</p>
                </div>
                <div class="progress-bar mb-2">
                    <div id="cleanupBar" style="width:0%"></div>
                </div>
                <p id="cleanupText">0% of reported waste cleaned</p>
            </section>
        </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/appbar.js') }}"></script>
    <script>
        // Example: Animate the cleanup bar (replace with real data as needed)
        document.addEventListener('DOMContentLoaded', function () {
            setTimeout(function () {
                document.getElementById('cleanupBar').style.width = '60%';
                document.getElementById('cleanupText').textContent = '60% of reported waste cleaned';
                document.getElementById('totalWaste').textContent = '1200 kg';
                document.getElementById('mostType').textContent = 'Plastic';
            }, 800);
        });
    </script>
</body>

</html>