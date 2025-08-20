<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Details</title>
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
        }
        .table {
            color: #ffffff;
        }
        .table thead th {
            background-color: #3a4147;
            color: #ffffff;
            border-color: #4a5157;
        }
        .table td {
            border-color: #4a5157;
            vertical-align: middle;
        }
        .content-wrapper {
            margin-left: 250px;
            padding: 20px;
        }
    </style>
</head>
<body>
    @include('admin.adminsidebar')
    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Volunteer Details</h2>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-dark table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Skills/Tools</th>
                                    <th>Registered At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($volunteers as $volunteer)
                                <tr>
                                    <td>{{ $volunteer->id }}</td>
                                    <td>{{ $volunteer->name }}</td>
                                    <td>{{ $volunteer->email }}</td>
                                    <td>{{ $volunteer->phone }}</td>
                                    <td>{{ $volunteer->address }}</td>
                                    <td>{{ $volunteer->tools }}</td>
                                    <td>{{ $volunteer->created_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
