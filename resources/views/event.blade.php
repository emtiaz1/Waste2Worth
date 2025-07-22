<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Event Signup</title>
<<<<<<< HEAD
  <link rel="stylesheet" href="{{ asset('css/style_fuad.css') }}" />
=======
  <link rel="stylesheet" href="public\css\style_fuad.css"/>
>>>>>>> 72d746128b7e22e8ee386d47157c8dcd5f0dc326
</head>
<body>
  <div class="container">
    <h1>Join a Cleanup Event</h1>
    <form id="eventForm">
      <input type="text" placeholder="Full Name" name="name" required />
      <input type="email" placeholder="Email" name="email" required />
      <select name="event" required>
        <option value="">Select an Event</option>
        <option value="park-cleanup">Park Cleanup</option>
        <option value="beach-cleanup">Beach Cleanup</option>
        <option value="city-street">City Street Cleaning</option>
      </select>
      <textarea placeholder="Any special skills or tools you'll bring?" name="tools"></textarea>
      <button type="submit">Sign Up</button>
    </form>
    <div id="eventMessage"></div>
  </div>
  <div id="backHomeBtn" style="display:none; text-align:center; margin-top: 20px;">
  <button onclick="location.href='main.html'">Back to Home</button>
  </div>
<<<<<<< HEAD
  <script src="{{ asset('js/script.js') }}"></script>
=======
  <script src="resources\js\script_fuad.js"></script>
>>>>>>> 72d746128b7e22e8ee386d47157c8dcd5f0dc326
</body>
</html>
