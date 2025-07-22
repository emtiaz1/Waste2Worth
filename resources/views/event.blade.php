<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Event Signup</title>
  <link rel="stylesheet" href="public\css\style_fuad.css"/>
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
  <script src="resources\js\script_fuad.js"></script>
</body>
</html>
