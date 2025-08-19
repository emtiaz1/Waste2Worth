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
                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="reward-image">
                            <div class="reward-info">
                                <h4 class="reward-name">{{ $product->name }}</h4>
                                <p class="reward-description">{{ $product->description }}</p>
                                <div class="reward-price">{{ $product->eco_coin_price }} EcoCoins</div>
                                <div class="reward-stock">Stock: {{ $product->stock }}</div>
                                <button class="btn-primary add-to-cart-btn" 
                                        onclick="addToCart({{ $product->id }})"
                                        {{ $product->stock > 0 ? '' : 'disabled' }}>
                                    {{ $product->stock > 0 ? 'Add to Cart' : 'Out of Stock' }}
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

            <!-- Cart Section -->
            <section class="card cart-section {{ $cartItems->isEmpty() ? 'hidden' : '' }}" id="cartSection">
                <h3><i class="fas fa-shopping-cart"></i> Your Cart ({{ $cartItems->sum('quantity') }} items)</h3>
                <div class="cart-items" id="cartItems">
                    @foreach($cartItems as $item)
                        <div class="cart-item" data-product-id="{{ $item->product_id }}">
                            <img src="{{ asset($item->product->image) }}" alt="{{ $item->product->name }}" class="cart-item-image">
                            <div class="cart-item-info">
                                <h4>{{ $item->product->name }}</h4>
                                <div class="cart-item-price">{{ $item->product->eco_coin_price }} EcoCoins each</div>
                                <div class="cart-item-quantity">Quantity: {{ $item->quantity }}</div>
                                <div class="cart-item-total">Total: {{ $item->quantity * $item->product->eco_coin_price }} EcoCoins</div>
                            </div>
                            <button class="btn-remove" onclick="removeFromCart({{ $item->product_id }})">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    @endforeach
                </div>
                <div class="cart-total">
                    <span>Total: <span id="cartTotal">{{ $cartTotal }}</span> EcoCoins</span>
                    <button class="btn-primary" id="purchaseBtn" onclick="showAddressModal()">Complete Purchase</button>
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
                        @foreach($purchaseHistory as $purchase)
                            <div class="history-item">
                                <div class="history-header">
                                    <span class="order-id">Order #{{ $purchase->id }}</span>
                                    <span class="order-date">{{ $purchase->created_at->format('M d, Y') }}</span>
                                    <span class="order-status delivered">{{ ucfirst($purchase->status) }}</span>
                                </div>
                                <div class="history-products">
                                    @foreach($purchase->products as $product)
                                        <div class="history-product">
                                            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="history-product-image">
                                            <div class="history-product-info">
                                                <h4>{{ $product->name }}</h4>
                                                <div class="product-details">
                                                    <span>Quantity: {{ $product->pivot->quantity }}</span>
                                                    <span>Price: {{ $product->eco_coin_price }} EcoCoins each</span>
                                                </div>
                                            </div>
                                            <div class="product-total">
                                                {{ $product->pivot->quantity * $product->eco_coin_price }} EcoCoins
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="history-footer">
                                    <div class="delivery-info">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span>Delivered to: {{ $purchase->delivery_address['street'] }}, {{ $purchase->delivery_address['city'] }}</span>
                                    </div>
                                    <div class="order-total">
                                        <strong>Total: {{ $purchase->total_amount }} EcoCoins</strong>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </section>
            </main>
        </div>

        <!-- Address Modal -->
                <!-- Address Modal -->
        <div class="modal hidden" id="addressModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-map-marker-alt"></i> Delivery Address</h3>
                    <button class="close-btn" onclick="closeAddressModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="addressForm" onsubmit="completePurchase(event)">
                        <div class="form-group">
                            <label for="street">Street Address *</label>
                            <input type="text" id="street" name="street" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="city">City *</label>
                                <input type="text" id="city" name="city" required>
                            </div>
                            <div class="form-group">
                                <label for="zipCode">ZIP Code *</label>
                                <input type="text" id="zipCode" name="zipCode" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone">
                        </div>
                        <div class="form-group">
                            <label for="notes">Delivery Notes</label>
                            <textarea id="notes" name="notes" rows="3" placeholder="Special delivery instructions..."></textarea>
                        </div>
                        <div class="modal-actions">
                            <button type="button" class="btn-secondary" onclick="closeAddressModal()">Cancel</button>
                            <button type="submit" class="btn-primary">Complete Purchase</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Success Modal -->
        <div class="modal hidden" id="successModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-check-circle"></i> Purchase Successful!</h3>
                    <button class="close-btn" onclick="closeSuccessModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="success-message">
                        <i class="fas fa-check-circle success-icon"></i>
                        <p>Your order has been placed successfully!</p>
                        <p>Your items will be delivered within 3-5 business days.</p>
                    </div>
                </div>
                <div class="modal-actions">
                    <button class="btn-primary" onclick="closeSuccessModal()">Continue Shopping</button>
                </div>
            </div>
        </div>

        <!-- Purchase Confirmation Modal -->
        <div class="modal hidden" id="confirmModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-check-circle"></i> Confirm Purchase</h3>
                    <button class="close-btn" onclick="closeConfirmModal()">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="confirm-details">
                        <h4>Order Summary</h4>
                        <div id="confirmItems"></div>
                        <div class="confirm-total">
                            <strong>Total: <span id="confirmTotal">0</span> EcoCoins</strong>
                        </div>
                        <div class="delivery-info">
                            <h4>Delivery Address</h4>
                            <div id="deliveryAddress"></div>
                        </div>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn-secondary" onclick="closeConfirmModal()">Cancel</button>
                        <button type="button" class="btn-primary" onclick="completePurchase()">Confirm Purchase</button>
                    </div>
                </div>
            </div>
        </div>

        <script src="{{ asset('js/reward.js') }}"></script>
        <script src="{{ asset('js/appbar.js') }}"></script>
</body>

</html>