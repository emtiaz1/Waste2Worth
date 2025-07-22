<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Waste2Worth - Community</title>
<<<<<<< HEAD
  <link rel="stylesheet" href="{{ asset('css/community.css') }}" />
=======
  <link rel="stylesheet" href="{{ asset('css/community.css') }}">
>>>>>>> 72d746128b7e22e8ee386d47157c8dcd5f0dc326
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
</head>
<body>
  <header class="header">
    <div class="container" style="display:flex; align-items:center; justify-content:space-between;">
      <div class="logo-section" style="display:flex; align-items:center;">
        <img src="{{ asset('frontend/image/logo.png') }}" alt="Waste2Worth Logo" class="logo" style="height:40px; margin-right:12px;">
        <span class="logo-text" style="font-family:'Playfair Display',serif; font-size:1.5em; font-weight:bold;">Waste2Worth</span>
      </div>
      <nav>
        <ul style="display:flex; gap:20px; margin:0;">
          <li><a href="{{ url('/home') }}">Home</a></li>
          <li><a href="{{ url('/event') }}">Events</a></li>
          <li><a href="{{ url('/market') }}">Marketplace</a></li>
          <li><a href="{{ url('/community') }}" class="active">Community</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <main class="container">
    <section class="intro">
      <h2>Welcome to Our Community</h2>
      <p>Connect, share, and collaborate with eco-warriors working together to transform waste into worth. Join groups, discuss ideas, and take part in sustainability campaigns.</p>
    </section>

    <section class="community-actions">
      <div class="card">
        <h3>👥 Join Groups</h3>
        <p>Find like-minded individuals and join waste management and recycling groups in your area.</p>
        <button>Explore Groups</button>
      </div>
      <div class="card">
        <h3>🗣️ Forum Discussions</h3>
        <p>Share ideas, ask questions, and discuss solutions with the Waste2Worth community.</p>
        <button>Go to Forum</button>
      </div>
      <div class="card">
        <h3>📅 Volunteer Events</h3>
        <p>Participate in community clean-ups and awareness events. Earn EcoPoints!</p>
        <a href="{{ url('/event') }}">
          <button>Find Events</button>
        </a>
      </div>
    </section>

    <section class="testimonials">
      <h2>Community Comments</h2>
      <div class="testimonial">
        <span class="user">Andrej Csizmadia</span>
        <div class="comment">I am very much interested in sustainable living practices.</div>
        <div class="actions">
          <span>Like</span>
          <span>Reply</span>
          <span>Share</span>
        </div>
      </div>
      <div class="testimonial">
        <span class="user">Sadia Khandaker</span>
        <div class="comment">Hello,<br>I just want to let you know that I am very interested in joining the community.</div>
        <div class="actions">
          <span>Like</span>
          <span>Reply</span>
          <span>Share</span>
        </div>
      </div>
    </section>
  </main>

  <footer class="footer">
    <div class="container">
      <p>&copy; 2025 Waste2Worth. All rights reserved.</p>
    </div>
  </footer>
</body>
</html>
