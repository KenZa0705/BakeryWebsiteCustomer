// JavaScript to validate form fields before submission
function validateForm() {
    var fname = document.getElementById("fname").value;
    var email = document.getElementById("email").value;
    var lname = document.getElementById("lname").value;
    var password = document.getElementById("password").value;
    var number = document.getElementById("number").value;
    var address = document.getElementById("address").value;

    if (fname === "" || email === "" || lname === "" || password === "" || number === "" || address === "") {
        alert("Please fill out all fields");
        return false;
    }
    return true;
}

// Utility function to display prompt messages
function promptMessage(message){
    alert(message);
}

// Function to retrieve URL parameters
function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
}

// Handling success and error messages based on URL parameters
var errorMessage = getUrlParameter('error');
if (errorMessage === 'query_failed') {
    var errorDiv = document.getElementById('errorMessage');
    errorDiv.textContent = "Error! Make sure you are not using an already registered email or phone";
    errorDiv.style.display = 'block';
}

var successMessage = getUrlParameter('accountcreation');
if (successMessage === 'success') {
    var successDiv = document.getElementById('success');
    successDiv.textContent = "Account Created Successfully!";
    successDiv.style.display = 'block';
}

// JavaScript to handle adding items to the cart and updating available stock
document.addEventListener("DOMContentLoaded", function() {
    const cartItems = document.querySelector('.cart-items');
    const cartTotalElement = document.querySelector('.cart-total');

    // Function to update the total price displayed in the sidebar
    function updateCartTotal() {
        let totalPrice = 0;
        const cartItemElements = document.querySelectorAll('.cart-item');
        cartItemElements.forEach(item => {
            const price = parseFloat(item.querySelector('span:nth-child(2)').textContent);
            const quantity = parseInt(item.querySelector('input[type="number"]').value);
            totalPrice += price * quantity;
        });
        cartTotalElement.textContent = '₱' + totalPrice.toFixed(2);
    }

    // Function to update the total quantity in the cart button
    function updateCartQuantity() {
        let totalQuantity = 0;
        const cartItemElements = document.querySelectorAll('.cart-item');
        cartItemElements.forEach(item => {
            const quantity = parseInt(item.querySelector('input[type="number"]').value);
            totalQuantity += quantity;
        });
        document.getElementById('cartTotalQuantity').textContent = totalQuantity;
    }

    // Add an item to the cart with stock check and quantity limit
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', () => {
            const name = button.getAttribute('data-name');
            const price = parseFloat(button.getAttribute('data-price'));
            const quantityAvailable = parseInt(button.getAttribute('data-quantity-available')); // New attribute
            const existingCartItem = cartItems.querySelector(`[data-name="${name}"]`);

            if (quantityAvailable === 0) {
                alert("Unable to add this item due to unavailable stock.");
                return;
            }

            if (existingCartItem) {
                const quantityElement = existingCartItem.querySelector('input[type="number"]');
                let quantity = parseInt(quantityElement.value);

                if (quantity < quantityAvailable) {
                    quantity++;  // Increment quantity if less than available stock
                    quantityElement.value = quantity;
                } else {
                    alert("Cannot add more items than available stock.");
                }
            } else {
                const item = `
                    <div class="cart-item" data-name="${name}">
                        <span>${name}</span>
                        <span>${price} PHP</span>
                        <input type="number" value="1" min="1" max="${quantityAvailable}"> 
                        <button class="remove-item">Remove</button>
                    </div>
                `;
                cartItems.insertAdjacentHTML('beforeend', item);
            }

            updateCartTotal();
            updateCartQuantity();
        });
    });

    // Handle cart item removals
    cartItems.addEventListener('click', (event) => {
        if (event.target.classList.contains('remove-item')) {
            const item = event.target.parentElement;
            item.remove();
            updateCartTotal();
            updateCartQuantity();
        }
    });

    // Handle input changes (quantity updates)
    cartItems.addEventListener('input', (event) => {
        if (event.target.tagName === 'INPUT') {
            const quantityElement = event.target;
            let quantity = parseInt(quantityElement.value);
            const maxQuantity = parseInt(quantityElement.getAttribute('max'));
            if (quantity < 1) {
                quantityElement.value = 1;  // Set minimum to 1
            } else if (quantity > maxQuantity) {
                quantityElement.value = maxQuantity;  // Set max to available stock
                alert("Cannot exceed available stock.");
            }
            updateCartTotal();
            updateCartQuantity();
        }
    });

    // Handle checkout process
    document.querySelector('.checkout-btn').addEventListener('click', function() {
        const total = cartTotalElement.textContent;

        const orderData = {
            totalPrice: parseFloat(total.replace('₱', '')),
            items: Array.from(document.querySelectorAll('.cart-item')).map(item => ({
                name: item.getAttribute('data-name'),
                quantity: parseInt(item.querySelector('input[type="number"]').value)
            }))
        };

        // AJAX call to process checkout
        fetch('includes/checkout.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(orderData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Order placed successfully!');
                cartItems.innerHTML = '';  // Clear the cart
                cartTotalElement.textContent = '₱0.00';  // Reset total price
                document.querySelector('#cartTotalQuantity').textContent = '0';
                document.getElementById('sidebar').style.display = 'none'; // Close the sidebar
            } else {
                alert('Error placing order: ' + data.message);
            }
        })
        .catch(err => console.error('Error:', err));
    });
});

// Add event listeners to ensure sidebar visibility
document.querySelector('#cartButton').addEventListener('click', () => {
    document.querySelector('#sidebar').style.display = 'block';
});

document.querySelector('#sidebarClose').addEventListener('click', () => {
    document.querySelector('#sidebar').style.display = 'none';
});


// Cancelling order
function confirmCancelOrder(orderId, product, quantity) {
    // Confirm with the user if they really want to cancel the order
    if (confirm("Are you sure you want to cancel order " + orderId + "?")) {
        // Create the POST body with multiple parameters
        const postData = new URLSearchParams({
            order_id: orderId,
            product: product, // Add the product parameter
            quantity: quantity // Add the quantity parameter
        });

        // If confirmed, perform an AJAX call to cancel the order
        fetch('cancel_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: postData.toString() // Ensure proper encoding
        })
        .then(response => response.text())
        .then(data => {
            alert(data); // Notify user about the cancellation result
            // Reload the cancelable orders table
            window.location.reload(); // Reload the page or specific section
        })
        .catch(error => console.error('Error:', error));
    }
}


// Redirection Functions
function logout() {
    window.location.href = 'includes/logout.php';  // Redirect to logout script
}
function accountsettings() {
    window.location.href = 'includes/accountsettings.php';  // Redirect to account settings script
}

// Functions to open/close login forms
function openBox(param) {
    document.getElementById(param).style.display = "block";
}

function closeBox(param) {
    document.getElementById(param).style.display = "none";
}