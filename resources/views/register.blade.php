<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Volunteer Registration</title>
  <link rel="stylesheet" href="style_fuad.css" />
</head>
<body>
  <div class="container">
    <h1>Register as Volunteer</h1>
    <form id="volunteerForm">
      <input type="text" placeholder="Full Name" name="name" required />
      <input type="email" placeholder="Email Address" name="email" required />
      <input type="tel" placeholder="Phone Number" name="phone" required />
      <input type="text" placeholder="Location/City" name="location" required />
      <textarea placeholder="Why do you want to volunteer?" name="reason" required></textarea>
      <button type="submit">Register</button>
    </form>
    <div id="volunteerMessage"></div>
  </div>
  <script src="script.js"></script>
</body>
</html>
