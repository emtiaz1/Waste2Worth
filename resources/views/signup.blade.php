<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign in & Sign up Form</title>
    <link rel="stylesheet" href="{{ asset('css/signup.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <main>
        @if(session('success'))
            <div class="notification success">
                <span class="notification-icon">✓</span>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="notification error">
                <span class="notification-icon">✕</span>
                {{ session('error') }}
            </div>
        @endif

        <div class="box">
            <div class="inner-box">
                <div class="forms-wrap">
                    <form action="{{ route('signin') }}" method="POST" autocomplete="off" class="sign-in-form">
                        @csrf
                        <div class="logo">
                            <img src="{{ asset('frontend/image/logo.png') }}" alt="Waste2Worth" />
                            <h4>Waste2Worth</h4>
                        </div>

                        <div class="heading">
                            <h2>Welcome Back</h2>
                            <h6>Not registred yet?</h6>
                            <a href="#" class="toggle">Sign up</a>
                        </div>

                        <div class="actual-form">
                            @if($errors->any())
                                <div class="alert alert-danger">
                                    <span style="margin-right: 5px;">⚠️</span>
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <div class="input-wrap">
                                <input type="email" name="email" class="input-field" autocomplete="off" required />
                                <label>Email</label>
                            </div>

                            <div class="input-wrap password-field">
                                <input type="password" name="password" class="input-field" autocomplete="off"
                                    required />
                                <label>Password</label>
                                <i class="password-toggle fas fa-eye-slash"></i>
                            </div>

                            <input type="submit" value="Sign In" class="sign-btn" />

                            <p class="text">
                                Forgotten your password or you login datails?
                                <a href="#">Get help</a> signing in
                            </p>

                            <div class="divider">
                                <span>or</span>
                            </div>

                            <a href="{{ route('google.login') }}" class="google-btn">
                                <img src="data:image/png;base64,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"
                                    alt="Google Logo" />
                                Continue with Google
                            </a>
                        </div>
                    </form>

                    <form action="{{ route('signup.store') }}" method="POST" autocomplete="off" class="sign-up-form">
                        @csrf
                        <div class="logo">
                            <img src="{{ asset('frontend/image/logo.png') }}" alt="Waste2Worth" />
                            <h4>Waste2Worth</h4>
                        </div>

                        <div class="heading" style="margin-bottom: 10px;">
                            <h2>Get Started</h2>
                            <h6>Already have an account?</h6>
                            <a href="#sign-in" class="toggle">Sign in</a>
                        </div>

                        <div class="actual-form">
                            <div class="input-wrap">
                                <input type="text" name="username" minlength="4" class="input-field" autocomplete="off"
                                    required />
                                <label>User Name</label>
                            </div>

                            <div class="input-wrap">
                                <input type="email" name="email" class="input-field" autocomplete="off" required />
                                <label>Email</label>
                            </div>

                            <div class="input-wrap password-field">
                                <input type="password" name="password" id="signup-password" minlength="4"
                                    class="input-field" autocomplete="off" required />
                                <label>Password</label>
                                <i class="password-toggle fas fa-eye-slash"></i>
                            </div>

                            <div class="input-wrap password-field">
                                <input type="password" name="password_confirmation" id="confirm-password" minlength="4"
                                    class="input-field" autocomplete="off" required />
                                <label>Confirm Password</label>
                                <i class="password-toggle fas fa-eye-slash"></i>
                            </div>

                            <div class="input-wrap">
                                <input type="text" name="present_address" class="input-field" autocomplete="off"
                                    required />
                                <label>Present Address</label>
                            </div>

                            <input type="submit" value="Sign Up" class="sign-btn" />

                            <p class="text">
                                By signing up, I agree to the
                                <a href="#">Terms of Services</a> and
                                <a href="#">Privacy Policy</a>
                            </p>
                        </div>
                    </form>
                </div>

                <div class="carousel">
                    <div class="images-wrapper">
                        <img src="frontend/image/log1.png" class="image img-1 show" alt="" />
                        <img src="frontend/image/log2.png" class="image img-2" alt="" />
                        <img src="frontend/image/log3.png" class="image img-3" alt="" />
                    </div>

                    <div class="text-slider">
                        <div class="text-wrap">
                            <div class="text-group">
                                <h2>Join Event With Us</h2>
                                <h2>Collect Waste Earn Rewards</h2>
                                <h2>Share Your Thoughts In Community</h2>
                            </div>
                        </div>

                        <div class="bullets">
                            <span class="active" data-value="1"></span>
                            <span data-value="2"></span>
                            <span data-value="3"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Javascript file -->

    <script src="{{ asset('js/signup.js') }}"></script>
    <script>
        // Password visibility toggle
        document.querySelectorAll('.password-toggle').forEach(toggle => {
            toggle.addEventListener('click', function () {
                const passwordInput = this.previousElementSibling.previousElementSibling;
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                } else {
                    passwordInput.type = 'password';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                }
            });
        });

        // Password confirmation validation
        const signupForm = document.querySelector('.sign-up-form');
        const password = document.getElementById('signup-password');
        const confirmPassword = document.getElementById('confirm-password');

        signupForm.addEventListener('submit', function (e) {
            if (password.value !== confirmPassword.value) {
                e.preventDefault();
                alert('Passwords do not match!');
                confirmPassword.focus();
            }
        });
    </script>
</body>

</html>