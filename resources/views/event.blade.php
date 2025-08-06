<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cleanup Events</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link rel="stylesheet" href="{{ asset('css/event.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/appbar.css') }}" />
</head>

<body>
  @include('layouts.appbar')
  <div class="layout" id="mainLayout">
    <div class="main-content">
      <div class="container" style="max-width: 1200px; background: transparent; box-shadow: none; padding: 20px;">
        <h1>Upcoming Cleanup Events</h1>

        <div class="event-grid">
          <!-- Event Card 1 -->
          <div class="event-card">
            <img src="{{ asset('frontend/image/clean1.jpg') }}" alt="Park Cleanup">
            <h2>Sher-e-Bangla Park Cleanup</h2>
            <div class="event-details">
              <p><i class="fas fa-map-marker-alt"></i> <strong>Location:</strong> Sher-e-Bangla Nagar Park, Agargaon</p>
              <p><i class="fas fa-clock"></i> <strong>Time:</strong> 8:00 AM - 12:00 PM</p>
              <p><i class="fas fa-calendar-alt"></i> <strong>Date:</strong> August 15, 2025</p>
            </div>
            <p>Join us to restore the natural beauty of Sher-e-Bangla Park. Gloves and bags provided.</p>
            <button onclick="location.href='{{ route('volunteer') }}?event=park-cleanup'">Sign Up</button>
          </div>

          <!-- Event Card 2 -->
          <div class="event-card">
            <img src="{{ asset('frontend/image/clean2.png') }}" alt="Beach Cleanup">
            <h2>Cox's Bazar Beach Cleanup</h2>
            <div class="event-details">
              <p><i class="fas fa-map-marker-alt"></i> <strong>Location:</strong> Cox's Bazar Sea Beach, Laboni Point</p>
              <p><i class="fas fa-clock"></i> <strong>Time:</strong> 6:00 AM - 10:00 AM</p>
              <p><i class="fas fa-calendar-alt"></i> <strong>Date:</strong> August 20, 2025</p>
            </div>
            <p>Help protect marine life by removing litter from Cox's Bazar beach. Volunteers welcome!</p>
            <button onclick="location.href='{{ route('volunteer') }}?event=beach-cleanup'">Sign Up</button>
          </div>

          <!-- Event Card 3 -->
          <div class="event-card">
            <img src="{{ asset('frontend/image/clean3.png') }}" alt="City Street Cleaning">
            <h2>Mirpur-1 Street Cleaning</h2>
            <div class="event-details">
              <p><i class="fas fa-map-marker-alt"></i> <strong>Location:</strong> Mirpur-1, Section-2, Main Road</p>
              <p><i class="fas fa-clock"></i> <strong>Time:</strong> 7:00 AM - 11:00 AM</p>
              <p><i class="fas fa-calendar-alt"></i> <strong>Date:</strong> August 25, 2025</p>
            </div>
            <p>Make our city cleaner! Join others in a fun and impactful community effort in Mirpur.</p>
            <button onclick="location.href='{{ route('volunteer') }}?event=city-street'">Sign Up</button>
          </div>

          <!-- Event Card 4 -->
          <div class="event-card">
            <img src="{{ asset('frontend/image/clean4.jpg') }}" alt="Riverbank Cleanup">
            <h2>Turag River Bank Cleanup</h2>
            <div class="event-details">
              <p><i class="fas fa-map-marker-alt"></i> <strong>Location:</strong> Turag River, Uttara Sector-18</p>
              <p><i class="fas fa-clock"></i> <strong>Time:</strong> 7:30 AM - 11:30 AM</p>
              <p><i class="fas fa-calendar-alt"></i> <strong>Date:</strong> August 30, 2025</p>
            </div>
            <p>Support local ecology by cleaning up along the Turag riverbank trail. All ages welcome.</p>
            <button onclick="location.href='{{ route('volunteer') }}?event=riverbank-cleanup'">Sign Up</button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/appbar.js') }}"></script>
</body>

</html>