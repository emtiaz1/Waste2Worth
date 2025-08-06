<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign in & Sign up Form</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
    <main>
        @if(session('success'))
            <div class="notification success" role="alert">
                <span class="notification-icon">
                    <i class="fas fa-check"></i>
                </span>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="notification error" role="alert">
                <span class="notification-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </span>
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
                            <h3>Welcome Back</h3>
                            <h6>Not registred yet?</h6>
                            <a href="#" class="toggle">Sign up</a>
                        </div>

                        <div class="actual-form">
                            @if($errors->any())
                                <div class="notification error" role="alert">
                                    <span class="notification-icon">
                                        <i class="fas fa-exclamation-circle"></i>
                                    </span>
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
                                <a
                                    href="mailto:emtiaz837@gmail.com?subject=Login%20Help%20Request&body=Hi%20Waste2Worth%20Team%2C%0A%0AI%20need%20help%20with%20my%20login.%20My%20email%20is%3A%20">Get
                                    help</a> signing in
                            </p>

                            <div style="text-align: center; margin: 1rem 0;">
                                <div class="divider">
                                    <span>or</span>
                                </div>

                                <a href="{{ route('google.login') }}" class="google-btn">
                                    <img src="{{ asset('frontend/image/Google.png') }}" alt="Google Logo"
                                        style="width: 2.5rem; height: 2.5rem; margin-top: 1rem;" />
                                </a>
                            </div>
                        </div>
                    </form>

                    <form action="{{ route('signup.store') }}" method="POST" autocomplete="off" class="sign-up-form">
                        @csrf
                        <div class="logo">
                            <img src="{{ asset('frontend/image/logo.png') }}" alt="Waste2Worth" />
                            <h4>Waste2Worth</h4>
                        </div>

                        <div class="heading" style="margin-bottom: 10px;">
                            <h3>Get Started</h3>
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
    <script src="{{ asset('js/login.js') }}"></script>
</body>

</html>