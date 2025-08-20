<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2c3338;
            color: #ffffff;
        }

        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }

        .card {
            background-color: #1a1d20;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
        }

        .card-body {
            color: #ffffff;
        }

        .stats-card {
            background-color: #2c3338;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            color: #ffffff;
        }

        .stats-card i {
            font-size: 2rem;
            margin-bottom: 10px;
        }

        h2,
        h3,
        h4,
        h5,
        .card-title {
            color: #ffffff !important;
        }

        p,
        .card-text {
            color: #ffffff !important;
        }

        .text-center {
            color: #ffffff;
        }
    </style>
</head>

<body>
    @include('admin.adminsidebar')

    <div class="content-wrapper">
        <div class="container-fluid">
            <h2 class="mb-4">Dashboard Overview</h2>

            <div class="row">
                <div class="col-md-3">
                    <div class="stats-card text-center">
                        <i class="fas fa-users text-primary"></i>
                        <h4>Total Users</h4>
                        <h3>0</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card text-center">
                        <i class="fas fa-recycle text-success"></i>
                        <h4>Waste Reports</h4>
                        <h3>0</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card text-center">
                        <i class="fas fa-box text-warning"></i>
                        <h4>Products</h4>
                        <h3>0</h3>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stats-card text-center">
                        <i class="fas fa-calendar text-info"></i>
                        <h4>Events</h4>
                        <h3>0</h3>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Welcome, {{ Auth::guard('admin')->user()->email }}!</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>