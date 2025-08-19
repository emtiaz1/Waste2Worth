document.addEventListener("DOMContentLoaded", function () {
    // Remove static data since we're now using dynamic data from the backend

    function addToCart(productId) {
        fetch("/rewards/cart/add", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Reload the page to show updated cart
                    location.reload();
                } else {
                    alert(data.message || "Failed to add item to cart");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("An error occurred while adding to cart");
            });
    }

    function removeFromCart(productId) {
        fetch("/rewards/cart/remove", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                product_id: productId,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Reload the page to show updated cart
                    location.reload();
                } else {
                    alert(data.message || "Failed to remove item from cart");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("An error occurred while removing from cart");
            });
    }

    function showAddressModal() {
        document.getElementById("addressModal").classList.remove("hidden");
    }

    function closeAddressModal() {
        document.getElementById("addressModal").classList.add("hidden");
    }

    function closeSuccessModal() {
        document.getElementById("successModal").classList.add("hidden");
        // Reload the page to show updated purchase history
        location.reload();
    }

    function completePurchase(event) {
        event.preventDefault();

        const form = document.getElementById("addressForm");
        const formData = new FormData(form);

        const addressData = {
            street: formData.get("street"),
            city: formData.get("city"),
            zipCode: formData.get("zipCode"),
            phone: formData.get("phone"),
            notes: formData.get("notes"),
        };

        fetch("/rewards/purchase/complete", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                delivery_address: addressData,
            }),
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    closeAddressModal();
                    document
                        .getElementById("successModal")
                        .classList.remove("hidden");
                } else {
                    alert(data.message || "Failed to complete purchase");
                }
            })
            .catch((error) => {
                console.error("Error:", error);
                alert("An error occurred while completing purchase");
            });
    }
});
