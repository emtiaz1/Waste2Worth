<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Purchase Management</title>
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

        .product-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 5px;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
        }

        .status-pending {
            background-color: #ffc107;
            color: #000;
        }

        .status-confirmed {
            background-color: #28a745;
            color: #fff;
        }

        .stats-card {
            background-color: #1a1d20;
            border: none;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.3);
            padding: 20px;
            text-align: center;
        }

        .stats-number {
            font-size: 2rem;
            font-weight: bold;
            color: #ffffff;
        }

        .stats-label {
            color: #aaa;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    @include('admin.adminsidebar')

    <div class="content-wrapper">
        <div class="container-fluid">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Purchase Management</h2>
            </div>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-number">{{ $totalPurchases ?? 0 }}</div>
                        <div class="stats-label">Total Purchase Requests</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-number text-warning">{{ $pendingCount ?? 0 }}</div>
                        <div class="stats-label">Pending Requests</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stats-card">
                        <div class="stats-number text-success">{{ $completedCount ?? 0 }}</div>
                        <div class="stats-label">Delivery Completed</div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Product</th>
                                    <th>Customer Name</th>
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Address</th>
                                    <th>EcoCoins Spent</th>
                                    <th>Status</th>
                                    <th>Order Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchases as $purchase)
                                    <tr>
                                        <td>#{{ $purchase->id }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('frontend/productimage/' . basename($purchase->product->image)) }}"
                                                    alt="{{ $purchase->product->name }}" class="product-image me-2">
                                                {{ $purchase->product->name }}
                                            </div>
                                        </td>
                                        <td>{{ $purchase->name }}</td>
                                        <td>{{ $purchase->email }}</td>
                                        <td>{{ $purchase->mobile }}</td>
                                        <td>{{ $purchase->address }}</td>
                                        <td>{{ $purchase->eco_coins_spent }}</td>
                                        <td>
                                            <span class="status-badge status-{{ $purchase->status }}">
                                                {{ ucfirst($purchase->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $purchase->created_at->format('M d, Y H:i') }}</td>
                                        <td>
                                            @if($purchase->status === 'pending')
                                                <form action="{{ route('admin.purchases.confirm', $purchase->id) }}"
                                                    method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-success btn-sm"
                                                        onclick="return confirm('Are you sure you want to confirm this order?')">
                                                        <i class="fas fa-check me-1"></i> Confirm
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-success btn-sm" disabled>
                                                    <i class="fas fa-check-circle me-1"></i> Confirmed
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>