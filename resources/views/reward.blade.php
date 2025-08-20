<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Rewards | Waste2Worth</title>
    <link rel="shortcut icon" href="/frontend/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/appbar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/reward.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .product-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 4px;
        }

        .purchase-form {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .purchase-form.active {
            display: block;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
        }

        .overlay.active {
            display: block;
        }

        .purchase-history {
            margin-top: 40px;
            padding: 20px;
        }

        .history-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
        }

        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal.hidden {
            display: none;
        }

        .modal-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 500px;
            width: 90%;
            max-height: 80vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .close-btn {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }

        .order-summary {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .product-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .modal-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .btn-primary,
        .btn-secondary {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .btn-primary {
            background: #28a745;
            color: white;
        }

        .btn-secondary {
            background: #6c757d;
            color: white;
        }

        .success-message {
            text-align: center;
            padding: 20px;
        }

        .success-icon {
            font-size: 48px;
            color: #28a745;
            margin-bottom: 20px;
        }

        .history-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }

        .delivery-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .delivery-address {
            display: flex;
            flex-direction: column;
            font-size: 0.9em;
        }

        .delivery-label {
            color: #666;
            font-size: 0.85em;
        }

        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 500;
            text-transform: uppercase;
            display: inline-block;
        }

        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .status-confirmed {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .history-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .history-table th,
        .history-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .history-table th {
            background-color: #f8f9fa;
            font-weight: 600;
            color: #333;
        }
    </style>
</head>

<body>
    @include('layouts.appbar')
    <div class="layout" id="mainLayout">
        <div class="main-content">
            <!-- EcoCoins Overview Card -->
            <section class="card ecocoins-card">
                <div class="ecocoins-header">
                    <h2><i class="fas fa-coins"></i> Your EcoCoins</h2>
                    <div class="balance-display"
                        style="display: flex; align-items: flex-end; justify-content: flex-end; flex-direction: column; text-align: right;">
                        <span class="balance-amount" id="ecoBalance">{{ number_format($totalEcoCoins) }}</span>
                        <span class="balance-label">EcoCoins Available</span>
                    </div>
                </div>
                <div class="recent-activities">
                    <h4>Recent Earnings</h4>
                    <ul class="activity-list">
                        @forelse($recentEarnings as $earning)
                            <li>
                                <span class="activity-desc">{{ $earning->reason }}</span>
                                <span class="activity-earn">+{{ $earning->eco_coin_value }} EcoCoins</span>
                                <span
                                    style="font-size: 10px; color: #ffffffff;">{{ $earning->created_at->format('M d, Y') }}</span>
                            </li>
                        @empty
                            <li>
                                <span class="activity-desc">No recent earnings</span>
                                <span class="activity-earn">Start earning EcoCoins!</span>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </section>

            <!-- Rewards Marketplace -->
            <section class="card rewards-marketplace">
                <h3><i class="fas fa-gift"></i> Rewards Marketplace</h3>
                <div class="rewards-grid" id="rewardsGrid">
                    @forelse($products as $product)
                        <div class="reward-item" data-product-id="{{ $product->id }}">
                            <img src="{{ asset('frontend/productimage/' . basename($product->image)) }}"
                                alt="{{ $product->name }}" class="reward-image">
                            <div class="reward-info">
                                <h4 class="reward-name">{{ $product->name }}</h4>
                                <p class="reward-description">{{ $product->description }}</p>
                                <div class="reward-price">{{ $product->eco_coin_value }} EcoCoins</div>
                                <div class="reward-stock">Stock: {{ $product->stock }}</div>
                                <button class="btn-primary order-btn"
                                    onclick="showOrderForm({{ $product->id }}, '{{ $product->name }}', {{ $product->eco_coin_value }})"
                                    {{ $product->stock > 0 ? '' : 'disabled' }}>
                                    {{ $product->stock > 0 ? 'Order Now' : 'Out of Stock' }}
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="no-products">
                            <p>No products available at the moment.</p>
                        </div>
                    @endforelse
                </div>
            </section>

            <!-- History Section -->
            <!-- Purchase History Section -->
            <section class="card history-section">
                <h3><i class="fas fa-history"></i> Purchase History</h3>
                @if($purchaseHistory->isEmpty())
                    <div class="empty-history">
                        <i class="fas fa-history"></i>
                        <p>No purchases yet. Start shopping to see your history here!</p>
                    </div>
                @else
                    <div class="history-items">
                        <table class="history-table">
                            <thead>
                                <tr>
                                    <th>Order Info</th>
                                    <th>Product Details</th>
                                    <th>Delivery Address</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($purchaseHistory as $purchase)
                                    <tr>
                                        <td>
                                            <div>Order #{{ $purchase->id }}</div>
                                            <div style="font-size: 0.85em; color: #666;">
                                                {{ $purchase->created_at->format('M d, Y g:i A') }}
                                            </div>
                                        </td>
                                        <td>
                                            <div style="display: flex; align-items: center; gap: 10px;">
                                                <img src="{{ asset('frontend/productimage/' . basename($purchase->product->image)) }}"
                                                    alt="{{ $purchase->product->name }}"
                                                    style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                                <div>
                                                    <div>{{ $purchase->product->name }}</div>
                                                    <div style="color: #666;">{{ $purchase->eco_coins_spent }} EcoCoins</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>{{ $purchase->name }}</div>
                                            <div style="color: #666;">{{ $purchase->address }}</div>
                                            <div style="color: #666;">{{ $purchase->mobile }}</div>
                                        </td>
                                        <td>
                                            <span
                                                class="status-badge {{ $purchase->status === 'confirmed' ? 'status-confirmed' : 'status-pending' }}">
                                                {{ ucfirst($purchase->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </section>
        </div>

        <!-- Order Form Modal -->
        <div class="modal hidden" id="orderModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-shopping-cart"></i> Place Order</h3>
                    <button class="close-btn" onclick="closeOrderModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="orderForm" action="{{ route('purchase.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" id="orderProductId">

                        <div class="order-summary">
                            <h4>Order Summary</h4>
                            <div class="product-info">
                                <span id="orderProductName"></span>
                                <span id="orderProductPrice" class="price"></span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="name">Full Name *</label>
                            <input type="text" id="name" name="name" required class="form-control">
                        </div>

                        <div class="form-group">
                            <label for="address">Address *</label>
                            <textarea id="address" name="address" required class="form-control" rows="3"
                                placeholder="Enter your complete address"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="mobile">Mobile Number *</label>
                            <input type="tel" id="mobile" name="mobile" required class="form-control"
                                placeholder="+880XXXXXXXXX">
                        </div>

                        <div class="modal-actions">
                            <button type="button" class="btn-secondary" onclick="closeOrderModal()">Cancel</button>
                            <button type="submit" class="btn-primary">Confirm Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div class="modal hidden" id="successModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-check-circle"></i> Order Successful!</h3>
                    <button class="close-btn" onclick="closeSuccessModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="success-message">
                        <i class="fas fa-check-circle success-icon"></i>
                        <p>Your order has been placed successfully!</p>
                        <p>Your item will be delivered to the provided address.</p>
                    </div>
                </div>
                <div class="modal-actions">
                    <button class="btn-primary" onclick="closeSuccessModal()">Continue Shopping</button>
                </div>
            </div>
        </div>

        <script>
            function showOrderForm(productId, productName, productPrice) {
                document.getElementById('orderProductId').value = productId;
                document.getElementById('orderProductName').textContent = productName;
                document.getElementById('orderProductPrice').textContent = productPrice + ' EcoCoins';
                document.getElementById('orderModal').classList.remove('hidden');
            }

            function closeOrderModal() {
                document.getElementById('orderModal').classList.add('hidden');
                document.getElementById('orderForm').reset();
            }

            function closeSuccessModal() {
                document.getElementById('successModal').classList.add('hidden');
                location.reload(); // Refresh to show updated data
            }

            // Show success modal if there's a success message
            @if(session('success'))
                document.addEventListener('DOMContentLoaded', function () {
                    document.getElementById('successModal').classList.remove('hidden');
                });
            @endif
        </script>
        <script src="https://cdn.jsdelivr.net/npm/font-awesome@6.0.0/js/all.min.js"></script>
        <script src="{{ asset('js/appbar.js') }}"></script>
</body>

</html>