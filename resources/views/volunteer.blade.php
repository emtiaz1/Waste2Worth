<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Volunteer Registration</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
  <link rel="stylesheet" href="{{ asset('css/appbar.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/volunteer.css') }}" />
</head>

<body>
  @include('layouts.appbar')
  <div class="layout" id="mainLayout">
    <div class="main-content">
      <div class="bg-overlay">
        <div class="container">
          <h1>Volunteer Registration</h1>
          <form id="registrationForm">
            <input type="text" name="name" placeholder="Full Name" required />
            <input type="email" name="email" placeholder="Email Address" required />
            <input type="tel" name="phone" placeholder="Phone Number" required />
            <input type="text" name="address" placeholder="Present Address" required />
            <textarea name="tools" placeholder="Any special skills or tools you'll bring?" rows="4"></textarea>
            <button type="submit" class="register-btn">Register</button>
          </form>
          <div id="eventMessage" style="display: none;"></div>
          
          <!-- Success message and events display -->
          <div id="successSection" style="display: none;">
            <div class="success-message">
              <h2><i class="fas fa-check-circle"></i> Registration Successful!</h2>
              <p>Thank you for registering as a volunteer. You can join additional events below.</p>
            </div>
            
            <!-- Display all events with registration status -->
            <div class="registered-events">
              <h3>Available Events:</h3>
              <div class="event-list">
                <!-- Events will be populated by JavaScript -->
              </div>
            </div>
            
            <!-- Action buttons -->
            <div class="action-buttons">
              <button onclick="window.location.href='{{ route('home') }}'" class="btn-home">
                <i class="fas fa-home"></i> Go to Dashboard
              </button>
              <button onclick="window.location.href='{{ route('event') }}'" class="btn-events">
                <i class="fas fa-calendar"></i> View All Events
              </button>
              <button onclick="resetRegistration()" class="btn-reset">
                <i class="fas fa-refresh"></i> Reset Registration
              </button>
            </div>
          </div>
          <div class="back-btn">
            <button onclick="window.location.href='{{ route('event') }}'">⬅ Back to Events</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/script_fuad.js') }}"></script>
  <script src="{{ asset('js/appbar.js') }}"></script>
</body>

</html>