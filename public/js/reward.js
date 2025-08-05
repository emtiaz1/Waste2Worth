document.addEventListener("DOMContentLoaded", function () {
    let ecoBalance = 2450;
    let cart = [];

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
            name: "Plant",
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
            name: "500 taka Voucher",
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

    purchaseBtn.addEventListener("click", function() {
        const total = cart.reduce((sum, item) => sum + item.cost, 0);
        if (ecoBalance >= total && cart.length > 0) {
            ecoBalance -= total;
            updateBalance();
            addToHistory(cart);
            cart = [];
            cartSection.classList.add("hidden");
            renderRewards();
            showSuccessMessage();
        }
    });

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
            Purchase successful! Thank you for supporting sustainability 🌱
        `;
        document.body.appendChild(notification);

        setTimeout(() => {
            notification.remove();
        }, 4000);
    }

    // Initial render
    updateBalance();
    renderRewards();
});