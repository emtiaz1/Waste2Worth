<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Cleanup Events</title>
  <link rel="stylesheet" href="{{ asset('css/style_fuad.css') }}" />
</head>
<body>
  <div class="container">
    <h1>Upcoming Cleanup Events</h1>
    
    <div class="event-grid">
      <!-- Event Card 1 -->
      <div class="event-card">
        <img src="images/park.jpg" alt="Park Cleanup">
        <h2>Park Cleanup</h2>
        <p>Join us to restore the natural beauty of the city park. Gloves and bags provided.</p>
        <button onclick="location.href='register.html?event=park-cleanup'">Sign Up</button>
      </div>

      <!-- Event Card 2 -->
      <div class="event-card">
        <img src="images/beach.jpg" alt="Beach Cleanup">
        <h2>Beach Cleanup</h2>
        <p>Help protect marine life by removing litter from the beach. Volunteers welcome!</p>
        <button onclick="location.href='register.html?event=beach-cleanup'">Sign Up</button>
      </div>

      <!-- Event Card 3 -->
      <div class="event-card">
        <img src="images/street.jpg" alt="City Street Cleaning">
        <h2>City Street Cleaning</h2>
        <p>Make our city cleaner! Join others in a fun and impactful community effort.</p>
        <button onclick="location.href='register.html?event=city-street'">Sign Up</button>
      </div>

      <!-- Event Card 4 -->
      <div class="event-card">
        <img src="images/river.jpg" alt="Riverbank Cleanup">
        <h2>Riverbank Cleanup</h2>
        <p>Support local ecology by cleaning up along the riverbank trail. All ages welcome.</p>
        <button onclick="location.href='register.html?event=riverbank-cleanup'">Sign Up</button>
      </div>
    </div>
  </div>
</body>
</html>
