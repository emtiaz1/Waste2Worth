<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Waste2Worth - Community-Led Environmental Action Network</title>
  <link rel="stylesheet" href="welcome.css">
</head>

<body>
  <!-- Header -->
  <header class="header">
    <div class="container header-container">
      <div class="logo-section">
        <img src="/frontend/logo.png" alt="Waste2Worth Logo" class="logo">
        <h1 class="logo-text">Waste2Worth</h1>
      </div>
      <nav class="nav">
        <a href="#features">Features</a>
        <a href="#how-it-works">How It Works</a>
        <a href="#impact">Our Impact</a>
        <a href="#join">Join Us</a>
      </nav>
      <div class="auth-links">
        <a href="/frontend/login.html" class="login">Log In</a>
        <a href="/frontend/signup.html" class="signup">Sign Up</a>
      </div>
    </div>
  </header>

  <!-- Hero Section -->
  <section class="hero">
    <div class="container hero-container">
      <div class="hero-text">
        <h2>Join the Movement for a Cleaner Environment</h2>
        <p>Waste2Worth empowers communities to take action against waste and pollution. Report, volunteer, and make a difference today.</p>
        <a href="#login" class="cta">Get Started</a>
      </div>
      <div class="hero-image">
        <img src="/frontend/bgL.png" alt="Clean Environment">
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="features">
    <div class="container">
      <h2>What You Can Do with Waste2Worth</h2>
      <div class="features-grid">
        <div class="feature-item">
          <h3>Report Waste</h3>
          <p>Easily report illegal dumps and pollution in your community.</p>
        </div>
        <div class="feature-item">
          <h3>Volunteer Locally</h3>
          <p>Join or organize clean-up events and sustainability drives.</p>
        </div>
        <div class="feature-item">
          <h3>Track Impact</h3>
          <p>See how your efforts contribute to a greener planet.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section id="how-it-works" class="how-it-works">
    <div class="container">
      <h2>How It Works</h2>
      <div class="steps">
        <div class="step">
          <h3>1. Sign Up</h3>
          <p>Create an account to access all features.</p>
        </div>
        <div class="step">
          <h3>2. Take Action</h3>
          <p>Report issues, join events, or start your own campaigns.</p>
        </div>
        <div class="step">
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
          <h3>1,200+</h3>
          <p>Pollution Reports</p>
        </div>
        <div class="impact-item">
          <h3>500+</h3>
          <p>Volunteers Engaged</p>
        </div>
        <div class="impact-item">
          <h3>100+</h3>
          <p>Successful Clean-ups</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Join Us Section -->
  <section id="join" class="join">
    <div class="container">
      <h2>Ready to Make a Difference?</h2>
      <p>Join thousands of people around the world creating a cleaner tomorrow.</p>
      <a href="/frontend/signup.html" class="cta">Join the Movement</a>
    </div>
  </section>

  <!-- Login Section -->
  <section id="login" class="login-section">
    <div class="container">
      <h2>Welcome Back!</h2>
      <p>Log in to your Waste2Worth account and continue making an impact.</p>
      <a href="/frontend/login.html" class="cta">Go to Login Page</a>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container footer-container">
      <div class="footer-col">
        <h3>Waste2Worth</h3>
        <p>Community-Led Environmental Action Network</p>
      </div>
      <div class="footer-col">
        <h4>Quick Links</h4>
        <ul>
          <li><a href="#">Home</a></li>
          <li><a href="#">About Us</a></li>
          <li><a href="#">Contact</a></li>
          <li><a href="#">Privacy Policy</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h4>Follow Us</h4>
        <div class="socials">
          <a href="#">Facebook</a>
          <a href="#">Twitter</a>
          <a href="#">Instagram</a>
          <a href="#">LinkedIn</a>
        </div>
      </div>
      <div class="footer-col">
        <h4>Newsletter</h4>
        <form>
          <input type="email" placeholder="Your email">
          <button type="submit">Subscribe</button>
        </form>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; 2024 Waste2Worth. All rights reserved.</p>
    </div>
  </footer>

  <script src="welcome.js"></script>
</body>

</html>
