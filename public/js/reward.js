document.addEventListener("DOMContentLoaded", function () {
    let ecoBalance = 2450;
    let cart = [];
    let deliveryAddress = null;

    const rewards = [
        {
            id: 1,
            name: "Reusable Water Bottle",
            cost: 500,
            image: "/frontend/image/bottle.jpg",
            description: "Eco-friendly stainless steel bottle"
        },
        {
            id: 2,
            name: "Organic Tote Bag",
            cost: 300,
            image: "/frontend/image/bag.jpg",
            description: "100% organic cotton bag"
        },
        {
            id: 3,
            name: "Plant a Tree",
            cost: 1000,
            image: "/frontend/image/plant.jpg",
            description: "Plant a tree in your name"
        },
        {
            id: 4,
            name: "Solar Power Bank",
            cost: 800,
            image: "/frontend/image/pb.jpg",
            description: "Portable solar charging device"
        },
        {
            id: 5,
            name: "Eco Cleaning Kit",
            cost: 450,
            image: "/frontend/image/kit.jpg",
            description: "Natural cleaning products set"
        },
        {
            id: 6,
            name: "Green Store Voucher",
            cost: 600,
            image: "/frontend/image/voucher.jpg",
            description: "20% off at partner eco stores"
        }
    ];

    const balanceEl = document.getElementById("ecoBalance");
    const rewardsGrid = document.getElementById("rewardsGrid");
    const cartSection = document.getElementById("cartSection");
    const cartItems = document.getElementById("cartItems");
    const cartTotal = document.getElementById("cartTotal");
    const purchaseBtn = document.getElementById("purchaseBtn");
    const addressModal = document.getElementById("addressModal");
    const confirmModal = document.getElementById("confirmModal");
    const addressForm = document.getElementById("addressForm");

    function updateBalance() {
        balanceEl.textContent = ecoBalance.toLocaleString();
    }

    function renderRewards() {
        rewardsGrid.innerHTML = "";
        rewards.forEach(reward => {
            const rewardEl = document.createElement("div");
            rewardEl.className = "reward-item";
            rewardEl.innerHTML = `
                <img src="${reward.image}" alt="${reward.name}" class="reward-image">
                <div class="reward-name">${reward.name}</div>
                <div class="reward-cost">${reward.cost} EcoCoins</div>
                <button class="btn-add-cart" onclick="addToCart(${reward.id})" 
                        ${ecoBalance < reward.cost ? 'disabled' : ''}>
                    ${ecoBalance < reward.cost ? 'Insufficient Coins' : 'Add to Cart'}
                </button>
            `;
            rewardsGrid.appendChild(rewardEl);
        });
    }

    // Add to cart directly (no address modal)
    window.addToCart = function(rewardId) {
        const reward = rewards.find(r => r.id === rewardId);
        if (reward && ecoBalance >= reward.cost) {
            cart.push(reward);
            renderCart();
            cartSection.classList.remove("hidden");
        }
    };

    function renderCart() {
        cartItems.innerHTML = "";
        let total = 0;

        cart.forEach((item, index) => {
            total += item.cost;
            const cartItem = document.createElement("div");
            cartItem.className = "cart-item";
            cartItem.innerHTML = `
                <div class="cart-item-info">
                    <div class="cart-item-name">${item.name}</div>
                    <div class="cart-item-cost">${item.cost} EcoCoins</div>
                </div>
                <button class="btn-remove" onclick="removeFromCart(${index})">Remove</button>
            `;
            cartItems.appendChild(cartItem);
        });

        cartTotal.textContent = total.toLocaleString();
    }

    window.removeFromCart = function(index) {
        cart.splice(index, 1);
        if (cart.length === 0) {
            cartSection.classList.add("hidden");
        }
        renderCart();
    };

    // Open address modal when confirming purchase
    purchaseBtn.addEventListener("click", function() {
        if (cart.length > 0) {
            if (!deliveryAddress) {
                // Show address modal first
                addressModal.classList.remove("hidden");
            } else {
                // Show confirmation modal if address already exists
                showConfirmationModal();
            }
        } else {
            alert("Please add items to cart first.");
        }
    });

    // Close address modal
    window.closeAddressModal = function() {
        addressModal.classList.add("hidden");
        addressForm.reset();
    };

    // Handle address form submission
    addressForm.addEventListener("submit", function(e) {
        e.preventDefault();
        
        deliveryAddress = {
            fullName: document.getElementById("fullName").value,
            phone: document.getElementById("phone").value,
            address: document.getElementById("address").value,
            city: document.getElementById("city").value,
            zipCode: document.getElementById("zipCode").value
        };

        closeAddressModal();
        // After getting address, show confirmation modal
        showConfirmationModal();
    });

    function showConfirmationModal() {
        // Populate confirmation items
        const confirmItemsEl = document.getElementById("confirmItems");
        confirmItemsEl.innerHTML = "";
        
        let total = 0;
        cart.forEach(item => {
            total += item.cost;
            const confirmItem = document.createElement("div");
            confirmItem.className = "confirm-item";
            confirmItem.innerHTML = `
                <span>${item.name}</span>
                <span>${item.cost} EcoCoins</span>
            `;
            confirmItemsEl.appendChild(confirmItem);
        });

        // Update total
        document.getElementById("confirmTotal").textContent = total.toLocaleString();

        // Display delivery address
        const deliveryEl = document.getElementById("deliveryAddress");
        deliveryEl.innerHTML = `
            <div class="address-display">
                <strong>${deliveryAddress.fullName}</strong><br>
                ${deliveryAddress.phone}<br>
                ${deliveryAddress.address}<br>
                ${deliveryAddress.city}, ${deliveryAddress.zipCode}
            </div>
        `;

        confirmModal.classList.remove("hidden");
    }

    // Close confirmation modal
    window.closeConfirmModal = function() {
        confirmModal.classList.add("hidden");
    };

    // Complete purchase
    window.completePurchase = function() {
        const total = cart.reduce((sum, item) => sum + item.cost, 0);
        if (ecoBalance >= total && cart.length > 0) {
            ecoBalance -= total;
            updateBalance();
            addToHistory(cart);
            cart = [];
            deliveryAddress = null;
            cartSection.classList.add("hidden");
            confirmModal.classList.add("hidden");
            renderRewards();
            showSuccessMessage();
        }
    };

    function addToHistory(items) {
        const historyList = document.getElementById("historyList");
        const today = new Date().toLocaleDateString('en-US', { 
            month: 'short', 
            day: 'numeric', 
            year: 'numeric' 
        });

        items.forEach(item => {
            const historyItem = document.createElement("div");
            historyItem.className = "history-item";
            historyItem.innerHTML = `
                <div class="history-info">
                    <span class="history-item-name">${item.name}</span>
                    <span class="history-date">${today}</span>
                </div>
                <span class="history-cost">-${item.cost} EcoCoins</span>
            `;
            historyList.insertBefore(historyItem, historyList.firstChild);
        });
    }

    function showSuccessMessage() {
        const notification = document.createElement("div");
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: #43a047;
            color: white;
            padding: 16px 24px;
            border-radius: 8px;
            font-weight: 600;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(67, 160, 71, 0.3);
        `;
        notification.innerHTML = `
            <i class="fas fa-check-circle"></i> 
            Order confirmed! Your items will be delivered soon 🚚
        `;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 4000);
    }

    // Close modal when clicking outside
    window.addEventListener("click", function(e) {
        if (e.target === addressModal) {
            closeAddressModal();
        }
        if (e.target === confirmModal) {
            closeConfirmModal();
        }
    });

    // Initial render
    updateBalance();
    renderRewards();
});