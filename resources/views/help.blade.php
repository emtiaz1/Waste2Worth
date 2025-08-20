<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Center - Waste2Worth</title>
    <link rel="shortcut icon" href="/frontend/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/appbar.css') }}">
    <style>
        *,
        ::after,
        ::before {
            box-sizing: border-box;
        }

        :root {
            --primary-color: #28a745;
            --secondary-color: #218838;
            --dark-color: #1e7e34;
            --light-color: #f8f9fa;
        }

        body {
            background: linear-gradient(135deg, #e8f5e9 0%, #c8e6c9 100%);
            font-family: 'Segoe UI', 'Roboto', Arial, sans-serif;
            color: #2e7d32;
            margin: 0;
        }



        .help-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .help-card {
            background: white;
            border-radius: 15px;
            padding: 2rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .help-card:hover {
            transform: translateY(-5px);
        }

        .help-card i {
            font-size: 2.5rem;
            color: var(--primary-color);
            margin-bottom: 1rem;
        }

        .help-card h3 {
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .faq-section {
            max-width: 800px;
            margin: 4rem auto;
            padding: 0 2rem;
        }

        .faq-item {
            background: white;
            margin-bottom: 1rem;
            border-radius: 10px;
            overflow: hidden;
        }

        .faq-question {
            padding: 1.5rem;
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-weight: 500;
        }

        .faq-answer {
            padding: 0 1.5rem;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
        }

        .faq-item.active .faq-answer {
            padding: 1.5rem;
            max-height: 500px;
        }

        .contact-section {
            background: var(--light-color);
            padding: 4rem 2rem;
            text-align: center;
        }

        .btn-contact {
            display: inline-block;
            padding: 1rem 2rem;
            background: var(--primary-color);
            color: white;
            text-decoration: none;
            border-radius: 30px;
            transition: background 0.3s ease;
        }

        .btn-contact:hover {
            background: var(--dark-color);
        }
    </style>
</head>

<body>
    @include('layouts.appbar')

    <div class="help-header" style="">
        <h1>How can we help you?</h1>
    </div>

    <div class="help-grid">
        <div class="help-card">
            <i class="fas fa-recycle"></i>
            <h3>Waste Management</h3>
            <p>Learn how to properly segregate and report waste for maximum eco-coins.</p>
        </div>

        <div class="help-card">
            <i class="fas fa-coins"></i>
            <h3>Eco-Coins System</h3>
            <p>Understand how to earn and redeem eco-coins through various activities.</p>
        </div>

        <div class="help-card">
            <i class="fas fa-gift"></i>
            <h3>Rewards Program</h3>
            <p>Discover exciting rewards and learn how to redeem them with your eco-coins.</p>
        </div>

        <div class="help-card">
            <i class="fas fa-users"></i>
            <h3>Community Guidelines</h3>
            <p>Read our community guidelines and best practices for participation.</p>
        </div>
    </div>


    <section class="faq-section">
        <h2>Frequently Asked Questions</h2>
        <div class="faq-item">
            <div class="faq-question">
                How do I start earning eco-coins?
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                Start earning eco-coins by:
                <ul>
                    <li>Reporting waste collection through our app</li>
                    <li>Participating in community clean-up events</li>
                    <li>Referring friends to join Waste2Worth</li>
                    <li>Completing educational modules about waste management</li>
                </ul>
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                How does the reward system work?
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                Our reward system converts your environmental efforts into eco-coins. These coins can be redeemed for
                various sustainable products in our marketplace. The more waste you manage responsibly, the more coins
                you earn!
            </div>
        </div>

        <div class="faq-item">
            <div class="faq-question">
                What types of waste can I report?
                <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
                You can report various types of waste including:
                <ul>
                    <li>Plastic waste</li>
                    <li>Paper and cardboard</li>
                    <li>Electronic waste</li>
                    <li>Metal waste</li>
                    <li>Glass waste</li>
                </ul>
            </div>
        </div>
    </section>

    <section class="contact-section">
        <h2>Still need help?</h2>
        <p>Our support team is always here to help you with any questions or concerns.</p>
        <a href="{{ url('/contact') }}" class="btn-contact">Contact Support</a>
    </section>

    <script>
        document.querySelectorAll('.faq-question').forEach(question => {
            question.addEventListener('click', () => {
                const item = question.parentElement;
                item.classList.toggle('active');
            });
        });

        // Search functionality
        document.querySelector('.search-box').addEventListener('input', function (e) {
            const searchTerm = e.target.value.toLowerCase();
            document.querySelectorAll('.faq-item').forEach(item => {
                const text = item.textContent.toLowerCase();
                item.style.display = text.includes(searchTerm) ? 'block' : 'none';
            });
        });
    </script>
    <script src="{{ asset('js/appbar.js') }}"></script>
</body>

</html>
