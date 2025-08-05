<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rewards | Waste2Worth</title>
    <link rel="shortcut icon" href="/frontend/logo.png" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/reward.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="layout">
        @include('components.sidebar')
        <main class="main-content">
            <!-- EcoCoins Overview Card -->
            <section class="card ecocoins-card">
                <div class="ecocoins-header">
                    <h2><i class="fas fa-coins"></i> Your EcoCoins</h2>
                    <div class="balance-display">
                        <span class="balance-amount" id="ecoBalance">2,450</span>
                        <span class="balance-label">EcoCoins Available</span>
                    </div>
                </div>
                <div class="recent-activities">
                    <h4>Recent Earnings</h4>
                    <ul class="activity-list">
                        <li>
                            <span class="activity-desc">Beach Cleanup Event</span>
                            <span class="activity-earn">+150 EcoCoins</span>
                        </li>
                        <li>
                            <span class="activity-desc">Waste Report Verified</span>
                            <span class="activity-earn">+75 EcoCoins</span>
                        </li>
                        <li>
                            <span class="activity-desc">Monthly Challenge</span>
                            <span class="activity-earn">+200 EcoCoins</span>
                        </li>
                    </ul>
                </div>
            </section>

            <!-- Rewards Marketplace -->
            <section class="card rewards-marketplace">
                <h3><i class="fas fa-gift"></i> Rewards Marketplace</h3>
                <div class="rewards-grid" id="rewardsGrid">
                    <!-- JS will populate rewards here -->
                </div>
            </section>

            <!-- Cart Section (Hidden by default) -->
            <section class="card cart-section hidden" id="cartSection">
                <h3><i class="fas fa-shopping-cart"></i> Your Cart</h3>
                <div class="cart-items" id="cartItems"></div>
                <div class="cart-total">
                    <span>Total: <span id="cartTotal">0</span> EcoCoins</span>
                    <button class="btn-primary" id="purchaseBtn">Complete Purchase</button>
                </div>
            </section>

            <!-- History Section -->
            <section class="card history-section">
                <h3><i class="fas fa-history"></i> Purchase History</h3>
                <div class="history-list" id="historyList">
                    <div class="history-item">
                        <div class="history-info">
                            <span class="history-item-name">Reusable Water Bottle</span>
                            <span class="history-date">Jan 15, 2025</span>
                        </div>
                        <span class="history-cost">-500 EcoCoins</span>
                    </div>
                    <div class="history-item">
                        <div class="history-info">
                            <span class="history-item-name">Eco-friendly Tote Bag</span>
                            <span class="history-date">Jan 10, 2025</span>
                        </div>
                        <span class="history-cost">-300 EcoCoins</span>
                    </div>
                </div>
            </section>
        </main>
    </div>

    <!-- Address Modal -->
    <div class="modal hidden" id="addressModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-map-marker-alt"></i> Delivery Address</h3>
                <button class="close-btn" onclick="closeAddressModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="addressForm">
                    <div class="form-group">
                        <label for="fullName">Full Name</label>
                        <input type="text" id="fullName" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Street Address</label>
                        <textarea id="address" rows="3" required></textarea>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="city">City</label>
                            <input type="text" id="city" required>
                        </div>
                        <div class="form-group">
                            <label for="zipCode">ZIP Code</label>
                            <input type="text" id="zipCode" required>
                        </div>
                    </div>
                    <div class="modal-actions">
                        <button type="button" class="btn-secondary" onclick="closeAddressModal()">Cancel</button>
                        <button type="submit" class="btn-primary">Add to Cart</button>
                    </div>
                </form>
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
</body>
</html>