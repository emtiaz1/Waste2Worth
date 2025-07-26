<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cleanup Events</title>
  <link rel="stylesheet" href="{{ asset('css/style_fuad.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}" />
</head>
<body>
  <div class="layout-flex">
    @include('components.sidebar')
    <div class="volunteer-content">
      <div class="container" style="max-width: 1200px; background: transparent; box-shadow: none; padding: 20px;">
        <h1>Upcoming Cleanup Events</h1>
        
        <div class="event-grid">
      <!-- Event Card 1 -->
      <div class="event-card">
        <img src="{{ asset('frontend/image/clean1.jpg') }}" alt="Park Cleanup">
        <h2>Park Cleanup</h2>
        <p>Join us to restore the natural beauty of the city park. Gloves and bags provided.</p>
        <button onclick="location.href='{{ route('volunteer') }}?event=park-cleanup'">Sign Up</button>
      </div>

      <!-- Event Card 2 -->
      <div class="event-card">
        <img src="{{ asset('frontend/image/clean2.png') }}" alt="Beach Cleanup">
        <h2>Beach Cleanup</h2>
        <p>Help protect marine life by removing litter from the beach. Volunteers welcome!</p>
        <button onclick="location.href='{{ route('volunteer') }}?event=beach-cleanup'">Sign Up</button>
      </div>

      <!-- Event Card 3 -->
      <div class="event-card">
        <img src="{{ asset('frontend/image/clean3.png') }}" alt="City Street Cleaning">
        <h2>City Street Cleaning</h2>
        <p>Make our city cleaner! Join others in a fun and impactful community effort.</p>
        <button onclick="location.href='{{ route('volunteer') }}?event=city-street'">Sign Up</button>
      </div>

      <!-- Event Card 4 -->
      <div class="event-card">
        <img src="{{ asset('frontend/image/clean4.jpg') }}" alt="Riverbank Cleanup">
        <h2>Riverbank Cleanup</h2>
        <p>Support local ecology by cleaning up along the riverbank trail. All ages welcome.</p>
        <button onclick="location.href='{{ route('volunteer') }}?event=riverbank-cleanup'">Sign Up</button>
      </div>
    </div>
      </div>
    </div>
  </div>
</body>
</html>
