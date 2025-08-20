<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Message</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #2c3338;
            color: #ffffff;
        }

        .table {
            color: #ffffff;
        }

        .table thead th {
            background-color: #3a4147;
            color: #ffffff;
            border-color: #4a5157;
        }

        .tbody td {
            color: #ffffff;
            border-color: #4a5157;
            vertical-align: middle;
        }
    </style>
</head>

<body>
    @include('admin.adminsidebar')
    <div class="main-content" style="margin-left:260px; padding:30px 20px;">
        <h2>Contact Messages</h2>
        <table class="table table-bordered table-striped">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($messages as $msg)
                    <tr>
                        <td style="color: #ffffff;">{{ $msg->id }}</td>
                        <td style="color: #ffffff;">{{ $msg->name }}</td>
                        <td style="color: #ffffff;">{{ $msg->email }}</td>
                        <td style="color: #ffffff;">{{ $msg->message }}</td>
                        <td style="color: #ffffff;">{{ $msg->created_at->format('Y-m-d H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No messages found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
