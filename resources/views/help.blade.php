<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Help - Waste2Worth</title>
    <link rel="stylesheet" href="{{ asset('css/help.css') }}">
</head>
<body>
    <header>
        <h1>Need Help?</h1>
        <nav>
            <a href="{{ url('/') }}">Home</a>
            <a href="{{ url('/help') }}" class="active">Help</a>
        </nav>
    </header>
    <main>
        <section class="help-container">
            <h2>How can we assist you?</h2>
            <p>Welcome to the Waste2Worth help page! Here you’ll find answers to common questions and resources to get the most out of our platform.</p>
            <ul class="faq-list">
                <li>
                    <strong>How do I register?</strong>
                    <p>Click the 'Sign Up' button on the homepage and fill in your details.</p>
                </li>
                <li>
                    <strong>How do I contact support?</strong>
                    <p>Email us at <a href="mailto:support@waste2worth.com">support@waste2worth.com</a>.</p>
                </li>
                <li>
                    <strong>How do I reset my password?</strong>
                    <p>Use the 'Forgot Password' link on the login page.</p>
                </li>
            </ul>
        </section>
    </main>
    <footer>
        <p>&copy; 2025 Waste2Worth. All rights reserved.</p>
    </footer>
</body>
</html>