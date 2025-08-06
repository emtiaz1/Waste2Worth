<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="{{ asset('css/appbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
</head>

<body>
    @include('layouts.appbar')
    <div class="layout" id="mainLayout">
        <div class="profile-container">
            @if(session('success'))
                <div class="notification success">{{ session('success') }}</div>
            @endif
            <div class="profile-header">
                <div class="profile-info">
                    <div class="profile-picture-container">
                        @php
                            $pic = $profile->profile_picture;
                            $picPath = $pic && $pic !== '' ? public_path($pic) : null;
                        @endphp
                        <img src="{{ asset(($pic && $pic !== '' && $picPath && file_exists($picPath)) ? $pic : 'frontend/image/dp.jpg') }}?t={{ time() }}"
                            alt="Profile Picture" class="profile-picture" />
                        <div class="edit-picture-overlay">
                            <i class="fas fa-camera"></i>
                        </div>
                    </div>
                    <div class="profile-details">
                        <h1 class="profile-name">{{ $profile->username ?? 'User' }}</h1>
                        <div class="profile-location">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $profile->location ?? 'Location not set' }}</span>
                        </div>
                        <p class="profile-status">{{ $profile->status ?? 'No status set' }}</p>
                    </div>
                </div>
                <button type="button" class="profile-edit-btn" onclick="toggleEditMode()">
                    <i class="fas fa-edit"></i>
                    <span>Edit Profile</span>
                </button>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-value">{{ $profile->contribution ?? 0 }}</div>
                    <div class="stat-label">Total Contributions</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">{{ $profile->total_token ?? 0 }}</div>
                    <div class="stat-label">Total Tokens</div>
                </div>
                <div class="stat-card">
                    <div class="stat-value">
                        {{ $profile->achievements ? count(explode(',', $profile->achievements)) : 0 }}
                    </div>
                    <div class="stat-label">Achievements</div>
                </div>
            </div>

            <div class="profile-display">
                <div class="achievements-section">
                    <h3>Achievements</h3>
                    <div class="achievements-grid">
                        @if($profile && $profile->achievements)
                            @foreach(explode(',', $profile->achievements) as $achievement)
                                <div class="achievement-card">
                                    <img src="{{ asset('frontend/image/achievement.png') }}" alt="Achievement"
                                        class="achievement-icon">
                                    <div class="achievement-name">{{ trim($achievement) }}</div>
                                </div>
                            @endforeach
                        @else
                            <div class="achievement-card">
                                <img src="{{ asset('frontend/image/achievement.png') }}" alt="Achievement"
                                    class="achievement-icon">
                                <div class="achievement-name">No achievements yet</div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="token-history">
                    <h3>Token History</h3>
                    <ul class="token-list">
                        @if($profile && $profile->token_usages)
                            @foreach($profile->token_usages as $usage)
                                <li class="token-item">
                                    <span>{{ $usage }}</span>
                                    <span class="token-date">{{ now()->format('M d, Y') }}</span>
                                </li>
                            @endforeach
                        @else
                            <li class="token-item">No token usage history.</li>
                        @endif
                    </ul>
                </div>
            </div>

            <form action="{{ url('profile') }}" method="POST" enctype="multipart/form-data" class="edit-form">
                @csrf
                @method('PUT')
                <input type="file" name="profile_picture" id="profile_picture" hidden>

                <div class="input-group">
                    <label for="location">Location</label>
                    <input type="text" name="location" id="location" value="{{ $profile->location ?? '' }}"
                        placeholder="Enter your location">
                </div>

                <div class="input-group">
                    <label for="status">Status</label>
                    <input type="text" name="status" id="status" value="{{ $profile->status ?? '' }}"
                        placeholder="What's on your mind?">
                </div>

                <div style="display: flex; justify-content: center;">
                    <button type="submit" class="save-btn">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <script src="{{ asset('js/appbar.js') }}"></script>
    <script>
        function toggleEditMode() {
            const container = document.querySelector('.profile-container');
            container.classList.toggle('edit-mode');
        }

        // Handle profile picture upload
        document.querySelector('.edit-picture-overlay').addEventListener('click', () => {
            document.getElementById('profile_picture').click();
        });

        document.getElementById('profile_picture').addEventListener('change', function (e) {
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    document.querySelector('.profile-picture').src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
</body>

</html>