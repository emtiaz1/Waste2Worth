<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Volunteer Registration</title>
  <link rel="stylesheet" href="{{ asset('css/style_fuad.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
  <div class="layout-flex">
    @include('components.sidebar')
    <div class="volunteer-content">
      <div class="volunteer-form-box">
        <h1>Register as Volunteer</h1>
        <form id="volunteerForm">
          <input type="text" placeholder="Full Name" name="name" required />
          <input type="email" placeholder="Email Address" name="email" required />
          <input type="tel" placeholder="Phone Number" name="phone" required />
          <input type="text" placeholder="Location/City" name="location" required />
          <textarea placeholder="Why do you want to volunteer?" name="reason" required rows="3"></textarea>
          <button type="submit">Register</button>
        </form>
        <div id="volunteerMessage"></div>
        <div id="backHomeBtn" style="display:none;">
          <button onclick="#">Back to Home</button>
        </div>
      </div>
    </div>
  </div>
  <script src="{{ asset('js/script_fuad.js') }}"></script>
  <script src="{{ asset('js/sidebar.js') }}"></script>
</body>

</html>