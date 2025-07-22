<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Waste2Worth')</title>
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    @stack('head')
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
                <a href="/frontend/login.html" class="login">Log In</a>
                <a href="/frontend/signup.html" class="signup">Sign Up</a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        @yield('content')
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
    @stack('scripts')
</body>
</html>