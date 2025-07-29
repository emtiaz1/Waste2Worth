<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Volunteer Registration</title>
  <link rel="stylesheet" href="{{ asset('css/style_fuad.css') }}"/>
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}"/>
</head>
<body>
<<<<<<< HEAD
  <div style="display: flex;">@include('components.sidebar')
  <div class="bg-overlay">
    <div class="container">
      <h1>Volunteer Registration</h1>
      <form id="registrationForm">
        <input type="text" name="name" placeholder="Full Name" required />
        <input type="email" name="email" placeholder="Email Address" required />
        <input type="tel" name="phone" placeholder="Phone Number" required />
        <input type="text" name="address" placeholder="Present Address" required />
        <textarea name="tools" placeholder="Any special skills or tools you'll bring?" rows="4"></textarea>
        <button type="submit">Register</button>
      </form>
      <div id="eventMessage"></div>
      <div class="back-btn">
        <button onclick="window.location.href='{{ route('event') }}'">⬅ Back to Events</button>
=======
  <div class="layout-flex">
    @include('components.sidebar')
    <div class="volunteer-content">
      <div class="volunteer-form-box">
        <h1>Volunteer Registration</h1>
        <form id="registrationForm">
          <input type="text" name="name" placeholder="Full Name" required />
          <input type="email" name="email" placeholder="Email Address" required />
          <input type="tel" name="phone" placeholder="Phone Number" required />
          <input type="text" name="address" placeholder="Present Address" required />
          <textarea name="tools" placeholder="Any special skills or tools you'll bring?" rows="4"></textarea>
          <button type="submit">Register</button>
        </form>
        <div id="volunteerMessage"></div>
        <div id="backHomeBtn">
          <button onclick="window.location.href='{{ route('event') }}'">⬅ Back to Events</button>
        </div>
>>>>>>> afadab2fe29c37ea3fc05c67599c23b5be5f6e43
      </div>
    </div>
  </div></div>
  <script src="{{ asset('js/script_fuad.js') }}"></script>
  <script src="{{ asset('js/sidebar.js') }}"></script>
</body>
</html>
