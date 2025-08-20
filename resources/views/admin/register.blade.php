<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2c3338;
            color: #ffffff;
        }

        .card {
            background-color: #1a1d20;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            color: #ffffff;
        }

        .card-header {
            background-color: #2c3338;
            color: #ffffff;
            border-bottom: 1px solid #3a4147;
            padding: 1.5rem;
            font-size: 1.5rem;
            text-align: center;
        }

        .card-body {
            background-color: #1a1d20;
            color: #ffffff;
            padding: 2rem;
        }

        .form-control {
            background-color: #2c3338;
            border: 1px solid #3a4147;
            color: #ffffff;
        }

        .form-control:focus {
            background-color: #2c3338;
            border-color: #4a5157;
            color: #ffffff;
            box-shadow: 0 0 0 0.25rem rgba(255, 255, 255, 0.1);
        }

        .btn-primary {
            background-color: #3a4147;
            border-color: #3a4147;
            width: 100%;
            padding: 10px;
        }

        .btn-primary:hover {
            background-color: #4a5157;
            border-color: #4a5157;
        }

        .form-label {
            color: #ffffff;
        }

        a {
            color: #6c757d;
            text-decoration: none;
        }

        a:hover {
            color: #ffffff;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo-container i {
            font-size: 3rem;
            color: #ffffff;
            margin-bottom: 1rem;
        }

        .text-light,
        h2,
        h3,
        h4,
        h5,
        p {
            color: #ffffff !important;
        }

        .invalid-feedback {
            color: #ff6b6b;
        }

        .alert-success {
            background-color: #2c3338;
            border-color: #28a745;
            color: #ffffff;
        }
    </style>
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-user-plus me-2"></i>Admin Registration
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.register') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-2"></i>Email
                                </label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                                    name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Password
                                </label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                    id="password" name="password" required>
                                @error('password')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">
                                    <i class="fas fa-lock me-2"></i>Confirm Password
                                </label>
                                <input type="password" class="form-control" id="password_confirmation"
                                    name="password_confirmation" required>
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-user-plus me-2"></i>Register
                            </button>
                        </form>
                        <div class="mt-3 text-center">
                            <a href="{{ route('admin.login') }}">
                                <i class="fas fa-sign-in-alt me-2"></i>Already have an account? Login here
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>