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