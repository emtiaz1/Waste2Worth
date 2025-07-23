<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Waste2Worth - Contact Us</title>
    <link rel="shortcut icon" href="{{ asset('frontend/image/logo.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/contact.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('frontend/image/logo.png') }}" alt="Logo" class="sidebar-logo">
                <span class="sidebar-title">Waste2Worth</span>
            </div>
            <div class="sidebar-user">
                <img src="{{ asset('frontend/image/user.jpg') }}" alt="User" class="user-avatar">
                <span class="user-name">Hi, User!</span>
            </div>
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <h3>Account</h3>
                    <ul>
                        <li><a href="{{ url('/home') }}"><i class="fas fa-home"></i> Home</a></li>
                        <li><a href="#"><i class="fas fa-user"></i> Profile</a></li>
                        <li><a href="#"><i class="fas fa-heart"></i> Volunteer</a></li>
                    </ul>
                </div>
                <div class="nav-section">
                    <h3>Main</h3>
                    <ul>
                        <li></li><a href="{{ url('/event') }}"><i class="fas fa-calendar-alt"></i> Event</a></li>
                        <li><a href="{{ url('/community') }}"><i class="fas fa-users"></i> Community</a></li>
                        <li><a href="{{ url('/reportWaste') }}"><i class="fas fa-dumpster"></i> Waste Report</a></li>
                        <li><a href="#"><i class="fas fa-gift"></i> Reward</a></li>
                    </ul>
                </div>
                <div class="nav-section">
                    <h3>Support</h3>
                    <ul>
                        <li><a href="#"><i class="fas fa-flag"></i> Report</a></li>
                        <li><a href="{{ url('/help') }}"><i class="fas fa-circle-question"></i> Help</a></li>
                    </ul>
                </div>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <section class="card contact-card">
                <h2>Contact Us</h2>
                <p class="contact-intro">
                    Have questions, suggestions, or need support? Fill out the form below and our team will get back to you as soon as possible.
                </p>
                <form id="contactForm" class="contact-form" autocomplete="off">
                    <div class="form-group">
                        <label for="name"><i class="fas fa-user"></i> Name</label>
                        <input type="text" id="name" name="name" required />
                    </div>
                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email</label>
                        <input type="email" id="email" name="email" required />
                    </div>
                    <div class="form-group">
                        <label for="message"><i class="fas fa-comment-dots"></i> Message</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    <button type="submit"><i class="fas fa-paper-plane"></i> Send Message</button>
                    <div id="formMessage" class="form-message"></div>
                </form>
            </section>
        </main>
    </div>

    <script src="{{ asset('js/contact.js') }}"></script>
</body>
</html>