<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Waste2Worth</title>
    <link rel="stylesheet" href="{{ asset('css/appbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>

<body>
    @include('layouts.appbar')
    <div class="layout" id="mainLayout">
        <div class="profile-container">
            @if(session('success'))
                <div class="notification success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif
            
            @if(session('error'))
                <div class="notification error">
                    <i class="fas fa-exclamation-circle"></i>
                    {{ session('error') }}
                </div>
            @endif

            <!-- Profile Header -->
            <div class="profile-header">
                <div class="profile-info">
                    <div class="profile-picture-container">
                        @if($profile->profile_picture)
                            <img src="{{ asset('storage/' . $profile->profile_picture) }}?t={{ time() }}"
                                alt="Profile Picture" class="profile-picture" id="profilePictureDisplay" />
                        @else
                            <img src="{{ asset('frontend/image/dp.jpg') }}?t={{ time() }}"
                                alt="Profile Picture" class="profile-picture" id="profilePictureDisplay" />
                        @endif
                        <div class="edit-picture-overlay" onclick="openImageUploadModal()">
                            <i class="fas fa-camera"></i>
                            <span>Change Photo</span>
                        </div>
                    </div>
                    <div class="profile-details">
                        <h1 class="profile-name">
                            @if($profile->first_name || $profile->last_name)
                                {{ trim(($profile->first_name ?? '') . ' ' . ($profile->last_name ?? '')) }}
                            @elseif($profile->username)
                                {{ $profile->username }}
                            @else
                                {{ $profile->email }}
                            @endif
                        </h1>
                        <div class="profile-meta">
                            <div class="profile-location">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $profile->location ?? 'Location not set' }}</span>
                            </div>
                            @if($profile->organization)
                                <div class="profile-organization">
                                    <i class="fas fa-building"></i>
                                    <span>{{ $profile->organization }}</span>
                                </div>
                            @endif
                        </div>
                        <p class="profile-status">{{ $profile->status ?? 'No status set' }}</p>
                    </div>
                </div>
                <button type="button" class="profile-edit-btn" onclick="toggleEditMode()">
                    <i class="fas fa-edit"></i>
                    <span>Edit Profile</span>
                </button>
            </div>

            <!-- Quick Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-leaf"></i></div>
                    <div class="stat-value">{{ $profile->contribution ?? 0 }}</div>
                    <div class="stat-label">Contributions</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-coins"></i></div>
                    <div class="stat-value">{{ $profile->total_token ?? 0 }}</div>
                    <div class="stat-label">Eco Coins</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-recycle"></i></div>
                    <div class="stat-value">{{ $profile->waste_reports_count ?? 0 }}</div>
                    <div class="stat-label">Waste Reports</div>
                </div>
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-globe"></i></div>
                    <div class="stat-value">{{ number_format($profile->carbon_footprint_saved ?? 0, 1) }} kg</div>
                    <div class="stat-label">CO₂ Saved</div>
                </div>
            </div>

            <!-- Tab Navigation -->
            <div class="profile-tabs">
                <button class="tab-btn active" onclick="switchTab('overview')">
                    <i class="fas fa-user"></i> Overview
                </button>
                <button class="tab-btn" onclick="switchTab('personal')">
                    <i class="fas fa-id-card"></i> Personal Info
                </button>
                <button class="tab-btn" onclick="switchTab('activity')">
                    <i class="fas fa-chart-line"></i> Activity
                </button>
                <button class="tab-btn" onclick="switchTab('social')">
                    <i class="fas fa-share-alt"></i> Social Media
                </button>
            </div>

            <!-- Tab Content -->
            <div class="tab-content">
                <!-- Overview Tab -->
                <div id="overview-tab" class="tab-pane active">
                    <div class="profile-display">
                        <div class="section">
                            <h3><i class="fas fa-user-circle"></i> About</h3>
                            <div class="about-content">
                                @if($profile->bio)
                                    <p>{{ $profile->bio }}</p>
                                @else
                                    <p class="text-muted">No bio added yet.</p>
                                @endif
                            </div>
                        </div>

                        <div class="section">
                            <h3><i class="fas fa-trophy"></i> Achievements</h3>
                            <div class="achievements-grid">
                                @if($profile && $profile->achievements)
                                    @foreach(explode(',', $profile->achievements) as $achievement)
                                        <div class="achievement-card">
                                            <i class="fas fa-medal achievement-icon"></i>
                                            <div class="achievement-name">{{ trim($achievement) }}</div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="empty-state">
                                        <i class="fas fa-medal"></i>
                                        <p>No achievements yet. Start contributing to earn achievements!</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Info Tab -->
                <div id="personal-tab" class="tab-pane">
                    <div class="form-section">
                        <h3><i class="fas fa-id-card"></i> Personal Information</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <label>Email</label>
                                <span>{{ $profile->email }}</span>
                            </div>
                            <div class="info-item">
                                <label>Phone</label>
                                <span>{{ $profile->phone ?? 'Not provided' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Date of Birth</label>
                                <span>{{ $profile->date_of_birth ? $profile->date_of_birth->format('M d, Y') : 'Not provided' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Gender</label>
                                <span>{{ $profile->gender ? ucfirst(str_replace('_', ' ', $profile->gender)) : 'Not specified' }}</span>
                            </div>
                            <div class="info-item">
                                <label>Website</label>
                                <span>
                                    @if($profile->website)
                                        <a href="{{ $profile->website }}" target="_blank" rel="noopener">{{ $profile->website }}</a>
                                    @else
                                        Not provided
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Activity Tab -->
                <div id="activity-tab" class="tab-pane">
                    <div class="activity-section">
                        <h3><i class="fas fa-chart-line"></i> Activity Overview</h3>
                        
                        <div class="activity-stats">
                            <div class="activity-card">
                                <i class="fas fa-calendar-check"></i>
                                <div>
                                    <h4>{{ $profile->community_events_attended ?? 0 }}</h4>
                                    <p>Events Attended</p>
                                </div>
                            </div>
                            <div class="activity-card">
                                <i class="fas fa-clock"></i>
                                <div>
                                    <h4>{{ $profile->volunteer_hours ?? 0 }} hrs</h4>
                                    <p>Volunteer Time</p>
                                </div>
                            </div>
                            <div class="activity-card">
                                <i class="fas fa-leaf"></i>
                                <div>
                                    <h4>{{ number_format($profile->carbon_footprint_saved ?? 0, 1) }} kg</h4>
                                    <p>CO₂ Reduced</p>
                                </div>
                            </div>
                        </div>

                        <h4><i class="fas fa-history"></i> Token History</h4>
                        <div class="token-history">
                            @if($profile && $profile->token_usages && is_array($profile->token_usages) && count($profile->token_usages) > 0)
                                <ul class="token-list">
                                    @foreach($profile->token_usages as $usage)
                                        <li class="token-item">
                                            <span>{{ $usage }}</span>
                                            <span class="token-date">{{ now()->format('M d, Y') }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <div class="empty-state">
                                    <i class="fas fa-coins"></i>
                                    <p>No token usage history yet.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Social Media Tab -->
                <div id="social-tab" class="tab-pane">
                    <div class="social-section">
                        <h3><i class="fas fa-share-alt"></i> Social Media Links</h3>
                        @if($profile->social_links && is_array($profile->social_links) && count($profile->social_links) > 0)
                            <div class="social-links">
                                @foreach($profile->social_links as $platform => $url)
                                    @if(!empty($url))
                                        <a href="{{ $url }}" target="_blank" rel="noopener" class="social-link">
                                            <i class="fab fa-{{ $platform }}"></i>
                                            {{ ucfirst($platform) }}
                                        </a>
                                    @endif
                                @endforeach
                            </div>
                        @else
                            <div class="empty-state">
                                <i class="fas fa-share-alt"></i>
                                <p>No social media links added yet.</p>
                            </div>
                        @endif

                        @if($profile->preferred_causes && is_array($profile->preferred_causes) && count($profile->preferred_causes) > 0)
                            <h4><i class="fas fa-heart"></i> Preferred Causes</h4>
                            <div class="tags">
                                @foreach($profile->preferred_causes as $cause)
                                    <span class="tag cause-tag">{{ $cause }}</span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Edit Form -->
            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="edit-form">
                @csrf
                @method('PUT')
                
                <input type="file" name="profile_picture" id="profile_picture" hidden accept="image/*">

                <!-- Single Edit Container -->
                <div class="edit-container">
                    <!-- Basic Information -->
                    <div class="form-section">
                        <h3><i class="fas fa-user"></i> Basic Information</h3>
                        <div class="input-row">
                            <div class="input-group">
                                <label for="email">Email *</label>
                                <input type="email" name="email" id="email" value="{{ old('email', $profile->email) }}" required>
                            </div>
                            <div class="input-group">
                                <label for="username">Username</label>
                                <input type="text" name="username" id="username" value="{{ old('username', $profile->username) }}">
                            </div>
                        </div>

                        <div class="input-row">
                            <div class="input-group">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $profile->first_name) }}">
                            </div>
                            <div class="input-group">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name', $profile->last_name) }}">
                            </div>
                        </div>

                        <div class="input-row">
                            <div class="input-group">
                                <label for="location">Location</label>
                                <input type="text" name="location" id="location" value="{{ old('location', $profile->location) }}" placeholder="City, Country">
                            </div>
                            <div class="input-group">
                                <label for="status">Status</label>
                                <input type="text" name="status" id="status" value="{{ old('status', $profile->status) }}" placeholder="What's on your mind?">
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="bio">Bio</label>
                            <textarea name="bio" id="bio" rows="4" placeholder="Tell us about yourself...">{{ old('bio', $profile->bio) }}</textarea>
                        </div>
                    </div>

                    <!-- Personal Details -->
                    <div class="form-section">
                        <h3><i class="fas fa-id-card"></i> Personal Details</h3>
                        <div class="input-row">
                            <div class="input-group">
                                <label for="phone">Phone Number</label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone', $profile->phone) }}">
                            </div>
                            <div class="input-group">
                                <label for="date_of_birth">Date of Birth</label>
                                <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $profile->date_of_birth?->format('Y-m-d')) }}">
                            </div>
                        </div>

                        <div class="input-row">
                            <div class="input-group">
                                <label for="gender">Gender</label>
                                <select name="gender" id="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $profile->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $profile->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $profile->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                    <option value="prefer_not_to_say" {{ old('gender', $profile->gender) == 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                                </select>
                            </div>
                            <div class="input-group">
                                <label for="organization">Organization</label>
                                <input type="text" name="organization" id="organization" value="{{ old('organization', $profile->organization) }}">
                            </div>
                        </div>

                        <div class="input-group">
                            <label for="website">Website</label>
                            <input type="url" name="website" id="website" value="{{ old('website', $profile->website) }}" placeholder="https://example.com">
                        </div>
                    </div>

                    <!-- Preferred Causes -->
                    <div class="form-section">
                        <h3><i class="fas fa-heart"></i> Preferred Causes</h3>
                        <div class="input-group">
                            <label>Preferred Causes</label>
                            <!-- Hidden field to ensure preferred_causes is always sent -->
                            <input type="hidden" name="preferred_causes[]" value="">
                            <div class="checkbox-group">
                                @php
                                    $causes = ['Climate Action', 'Waste Reduction', 'Recycling', 'Community Clean-up', 'Environmental Education', 'Sustainable Living', 'Ocean Conservation', 'Green Energy'];
                                    $userCauses = $profile->preferred_causes ?? [];
                                    if (!is_array($userCauses)) $userCauses = [];
                                @endphp
                                @foreach($causes as $cause)
                                    <label class="checkbox-label">
                                        <input type="checkbox" name="preferred_causes[]" value="{{ $cause }}" 
                                               {{ in_array($cause, $userCauses) ? 'checked' : '' }}>
                                        <span>{{ $cause }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Social Media & Notifications -->
                    <div class="form-section">
                        <h3><i class="fas fa-share-alt"></i> Social Media & Notifications</h3>
                        
                        <h4>Social Media Links</h4>
                        <div class="input-row">
                            <div class="input-group">
                                <label for="facebook">Facebook</label>
                                <input type="url" name="social_links[facebook]" id="facebook" 
                                       value="{{ old('social_links.facebook', (is_array($profile->social_links ?? []) ? ($profile->social_links['facebook'] ?? '') : '')) }}" 
                                       placeholder="https://facebook.com/username">
                            </div>
                            <div class="input-group">
                                <label for="twitter">Twitter</label>
                                <input type="url" name="social_links[twitter]" id="twitter" 
                                       value="{{ old('social_links.twitter', (is_array($profile->social_links ?? []) ? ($profile->social_links['twitter'] ?? '') : '')) }}" 
                                       placeholder="https://twitter.com/username">
                            </div>
                        </div>

                        <div class="input-row">
                            <div class="input-group">
                                <label for="linkedin">LinkedIn</label>
                                <input type="url" name="social_links[linkedin]" id="linkedin" 
                                       value="{{ old('social_links.linkedin', (is_array($profile->social_links ?? []) ? ($profile->social_links['linkedin'] ?? '') : '')) }}" 
                                       placeholder="https://linkedin.com/in/username">
                            </div>
                            <div class="input-group">
                                <label for="instagram">Instagram</label>
                                <input type="url" name="social_links[instagram]" id="instagram" 
                                       value="{{ old('social_links.instagram', (is_array($profile->social_links ?? []) ? ($profile->social_links['instagram'] ?? '') : '')) }}" 
                                       placeholder="https://instagram.com/username">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="button" class="cancel-btn" onclick="toggleEditMode()">Cancel</button>
                    <button type="submit" class="save-btn">
                        <i class="fas fa-save"></i>
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Profile Picture Upload Modal -->
    <div id="imageUploadModal" class="image-modal">
        <div class="image-modal-content">
            <div class="image-modal-header">
                <h3>Update Profile Picture</h3>
                <span class="image-modal-close" onclick="closeImageUploadModal()">&times;</span>
            </div>
            <div class="image-modal-body">
                <div class="image-upload-area" id="imageUploadArea">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Click to upload or drag and drop</p>
                    <p class="upload-info">JPG, PNG, GIF up to 2MB</p>
                    <input type="file" id="imageUploadInput" accept="image/*" style="display: none;">
                </div>
                <div class="image-preview-container" id="imagePreviewContainer" style="display: none;">
                    <img id="imagePreview" src="" alt="Preview">
                    <div class="image-preview-actions">
                        <button type="button" class="btn-secondary" onclick="resetImageUpload()">Change Image</button>
                        <button type="button" class="btn-primary" onclick="uploadProfilePicture()">
                            <i class="fas fa-upload"></i>
                            Upload
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/appbar.js') }}"></script>
    <script>
        // Global variables
        let selectedImageFile = null;

        // Image Upload Modal Functions
        function openImageUploadModal() {
            document.getElementById('imageUploadModal').style.display = 'flex';
            resetImageUpload();
        }

        function closeImageUploadModal() {
            document.getElementById('imageUploadModal').style.display = 'none';
            resetImageUpload();
        }

        function resetImageUpload() {
            document.getElementById('imageUploadArea').style.display = 'block';
            document.getElementById('imagePreviewContainer').style.display = 'none';
            document.getElementById('imageUploadInput').value = '';
            selectedImageFile = null;
        }

        // Handle image upload
        async function uploadProfilePicture() {
            if (!selectedImageFile) return;

            const formData = new FormData();
            formData.append('profile_picture', selectedImageFile);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            try {
                const uploadBtn = document.querySelector('.btn-primary');
                uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
                uploadBtn.disabled = true;

                const response = await fetch('{{ route("profile.picture.update") }}', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    // Update the profile picture display
                    document.getElementById('profilePictureDisplay').src = result.image_url + '?t=' + new Date().getTime();
                    
                    // Also update sidebar profile picture if it exists
                    const sidebarProfilePic = document.querySelector('.sidebar-profile-picture');
                    if (sidebarProfilePic) {
                        sidebarProfilePic.src = result.image_url + '?t=' + new Date().getTime();
                    }

                    // Update home page profile picture if it exists
                    const homeProfilePic = document.querySelector('.profile-img-small');
                    if (homeProfilePic) {
                        homeProfilePic.src = result.image_url + '?t=' + new Date().getTime();
                    }
                    
                    closeImageUploadModal();
                    showNotification(result.message, 'success');
                } else {
                    throw new Error(result.error || 'Upload failed');
                }
            } catch (error) {
                console.error('Error uploading image:', error);
                showNotification('Failed to upload image. Please try again.', 'error');
            } finally {
                const uploadBtn = document.querySelector('.btn-primary');
                uploadBtn.innerHTML = '<i class="fas fa-upload"></i> Upload';
                uploadBtn.disabled = false;
            }
        }

        // Image upload area click handler
        document.getElementById('imageUploadArea').addEventListener('click', function() {
            document.getElementById('imageUploadInput').click();
        });

        // Handle file selection
        document.getElementById('imageUploadInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (2MB limit)
                if (file.size > 2 * 1024 * 1024) {
                    showNotification('File size must be less than 2MB', 'error');
                    return;
                }

                // Validate file type
                if (!file.type.startsWith('image/')) {
                    showNotification('Please select a valid image file', 'error');
                    return;
                }

                selectedImageFile = file;
                
                // Show preview
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                    document.getElementById('imageUploadArea').style.display = 'none';
                    document.getElementById('imagePreviewContainer').style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Drag and drop functionality
        const uploadArea = document.getElementById('imageUploadArea');
        
        uploadArea.addEventListener('dragover', function(e) {
            e.preventDefault();
            uploadArea.classList.add('drag-over');
        });

        uploadArea.addEventListener('dragleave', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('drag-over');
        });

        uploadArea.addEventListener('drop', function(e) {
            e.preventDefault();
            uploadArea.classList.remove('drag-over');
            
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                const file = files[0];
                document.getElementById('imageUploadInput').files = files;
                document.getElementById('imageUploadInput').dispatchEvent(new Event('change'));
            }
        });

        // Notification function
        function showNotification(message, type) {
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            notification.innerHTML = `<i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'}"></i> ${message}`;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }

        // Tab switching for main profile view
        function switchTab(tabName) {
            // Remove active class from all tabs and panes
            document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active'));
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.remove('active'));
            
            // Add active class to clicked tab and corresponding pane
            event.target.classList.add('active');
            document.getElementById(tabName + '-tab').classList.add('active');
        }



        // Toggle edit mode
        function toggleEditMode() {
            const container = document.querySelector('.profile-container');
            container.classList.toggle('edit-mode');
        }

        // Handle profile picture upload (legacy - keeping for form submission)
        document.querySelector('.edit-picture-overlay').addEventListener('click', () => {
            // This is now handled by openImageUploadModal() onclick
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

        // Auto-hide notifications
        setTimeout(() => {
            const notifications = document.querySelectorAll('.notification');
            notifications.forEach(notification => {
                notification.style.opacity = '0';
                setTimeout(() => notification.remove(), 300);
            });
        }, 5000);

        // Close modal when clicking outside
        window.addEventListener('click', function(e) {
            const modal = document.getElementById('imageUploadModal');
            if (e.target === modal) {
                closeImageUploadModal();
            }
        });
    </script>
</body>

</html>