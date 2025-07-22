<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contact Us | Waste2Worth</title>
    <!-- Main Contact Page CSS -->
    <link rel="stylesheet" href="css/contact.css">
    <!-- Add your site-wide CSS here if needed -->
</head>
<body>
    <!-- Header and Navigation -->
    <header>
        <nav>
            <a href="index.html">Home</a>
            <a href="about.html">About</a>
            <a href="contact.html" class="active">Contact</a>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <section class="contact-section">
            <h1>Contact Us</h1>
            <form id="contactForm" class="contact-form" autocomplete="off">
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" id="name" name="name" required />
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required />
                </div>
                <div class="form-group">
                    <label for="message">Message</label>
                    <textarea id="message" name="message" rows="5" required></textarea>
                </div>
                <button type="submit">Send Message</button>
                <div id="formMessage" class="form-message"></div>
            </form>
        </section>
    </main>

    <!-- Footer -->
    <footer>
        <p>&copy; 2025 Waste2Worth. All rights reserved.</p>
    </footer>

    <!-- Contact Page JavaScript -->
    <script src="js/contact.js"></script>
</body>
</html>