<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Welcome | Waste2Worth</title>
  <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
</head>

<body>

  <!-- Header/Menu -->
  <header class="header">
    <div class="container header-container">
      <div class="logo-section">
        <img src="{{ asset('frontend/image/logo.png') }}" alt="Waste2Worth Logo" class="logo">
        <h1 class="logo-text">Waste2Worth</h1>
      </div>
      <nav class="nav">
        <a href="{{ url('/') }}">Home</a>
        <a href="#features">Features</a>
        <a href="#how-it-works">How It Works</a>
        <a href="#impact">Our Impact</a>
        <a href="#join">Join Us</a>
      </nav>
      <div class="auth-links">
        <a href="{{ url('/login') }}?mode=signin" class="login">Log In</a>
        <a href="{{ url('/login') }}?mode=signup" class="signup">Sign Up</a>
      </div>
    </div>
  </header>

  <!-- Main Content -->
  <main>
    <!-- Hero Section -->
    <section class="hero">
      <div class="container hero-container">
        <div class="hero-text">
          <h1>Join the Movement for a Cleaner Environment</h1>
          <p>Waste2Worth empowers communities to take action against waste and pollution. Report, volunteer, and make a
            difference today.</p>
          <a href="#login" class="cta"><i class="fas fa-play-circle"></i> Get Started</a>
        </div>
        <div class="hero-image">
          <img src="{{ asset('frontend/image/w2w.png') }}" height="300" width="450" alt="Clean Environment">
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
      <div class="container">
        <h2>What You Can Do with Waste2Worth</h2>
        <div class="features-grid">
          <div class="feature-item">
            <i class="fas fa-exclamation-triangle fa-2x icon"></i>
            <h3>Report Waste</h3>
            <p>Easily report illegal dumps and pollution in your community.</p>
          </div>
          <div class="feature-item">
            <i class="fas fa-hands-helping fa-2x icon"></i>
            <h3>Volunteer Locally</h3>
            <p>Join or organize clean-up events and sustainability drives.</p>
          </div>
          <div class="feature-item">
            <i class="fas fa-chart-line fa-2x icon"></i>
            <h3>Track Impact</h3>
            <p>See how your efforts contribute to a greener planet.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="features">
      <div class="container">
        <h2>How It Works</h2>
        <div class="features-grid">
          <div class="feature-item">
            <i class="fas fa-user-plus fa-2x icon"></i>
            <h3>1. Sign Up</h3>
            <p>Create an account to access all features.</p>
          </div>
          <div class="feature-item">
            <i class="fas fa-hand-holding-heart fa-2x icon"></i>
            <h3>2. Take Action</h3>
            <p>Report issues, join events, or start your own campaigns.</p>
          </div>
          <div class="feature-item">
            <i class="fas fa-award fa-2x icon"></i>
            <h3>3. Earn Rewards</h3>
            <p>Gain recognition and earn badges for your contributions.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Impact Section -->
    <section id="impact" class="impact">
      <div class="container">
        <h2>Our Collective Impact</h2>
        <div class="impact-grid">
          <div class="impact-item">
            <h3><i class="fas fa-dumpster-fire"></i> 1,200+</h3>
            <p>Pollution Reports</p>
          </div>
          <div class="impact-item">
            <h3><i class="fas fa-users"></i> 500+</h3>
            <p>Volunteers Engaged</p>
          </div>
          <div class="impact-item">
            <h3><i class="fas fa-broom"></i> 100+</h3>
            <p>Successful Clean-ups</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Join Us Section -->
    <section id="join" class="join">
      <selection id="login">
        <div class="container">
        <h2>Ready to Make a Difference?</h2>
        <p>Join thousands of people around the world creating a cleaner tomorrow.</p>
        <a href="{{ url('/login') }}?mode=signin" class="cta"><i class="fas fa-users"></i> Join the Movement</a>
      </div>
      </selection>
    </section>

    <!-- Gallery Section -->
    <section id="gallery" class="gallery">
      <div class="container">
        <h2>See Our Work in Action</h2>
        <div class="gallery-grid">
          <div class="gallery-item">
            <img src="{{ asset('frontend/image/clean1.jpg') }}" alt="Community Cleanup">
            <p>Community Cleanup Drive</p>
          </div>
          <div class="gallery-item">
            <img src="{{ asset('frontend/image/clean2.png') }}" alt="Plastic Collection">
            <p>Plastic Waste Collection</p>
          </div>
          <div class="gallery-item">
            <img src="{{ asset('frontend/image/clean3.png') }}" alt="Awareness Campaign">
            <p>Awareness Campaign</p>
          </div>
          <div class="gallery-item">
            <img src="{{ asset('frontend/image/clean4.jpg') }}" alt="Volunteers">
            <p>Our Volunteers in Action</p>
          </div>
          <div class="gallery-item">
            <img src="{{ asset('frontend/image/clean5.png') }}" alt="Recycling Efforts">
            <p>Recycling Efforts</p>
          </div>
          <div class="gallery-item">
            <img src="{{ asset('frontend/image/tree.jpeg') }}" alt="Tree Planting">
            <p>Tree Planting Event</p>
          </div>
          <!-- Add more images as needed -->
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <footer class="footer">
    <div class="container footer-container">
      <div class="footer-col">
        <h3><i class="fas fa-leaf"></i> Waste2Worth</h3>
        <p>Community-Led Environmental Action Network</p>
      </div>
      <div class="footer-col">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="{{ url('/') }}">Home</a></li>
          <li><a href="#">About Us</a></li>
          <li><a href="#">Contact</a></li>
          <li><a href="#">Privacy Policy</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Follow Us</h4>
        <div class="socials">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>
      <div class="footer-col">
        <h4><i class="fas fa-envelope"></i> Newsletter</h4>
        <form>
          <input type="email" placeholder="Your email">
          <button type="submit"><i class="fas fa-paper-plane"></i> Subscribe</button>
        </form>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2025 Waste2Worth. All rights reserved.</p>
    </div>
  </footer>
  <script src="{{ asset('js/welcome.js') }}"></script>
</body>

</html>