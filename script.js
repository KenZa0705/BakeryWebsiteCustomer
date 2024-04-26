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

// JavaScript code to handle sidebar visibility
document.addEventListener("DOMContentLoaded", function () {
    var sidebar = document.getElementById('sidebar');
    var cartButton = document.getElementById('cartButton');
    var sidebarClose = document.getElementById('sidebarClose');
    const cartItems = document.querySelector('.cart-items');
    const cartTotalElement = document.querySelector('.cart-total');

    // Function to update the total price displayed in the sidebar
    function updateCartTotal() {
        let totalPrice = 0;  // Variable to accumulate the total price
        const cartItemElements = document.querySelectorAll('.cart-item');
        cartItemElements.forEach(item => {
            const price = parseFloat(item.querySelector('span:nth-child(2)').textContent); // Product price
            const quantity = parseInt(item.querySelector('input[type="number"]').value); // Product quantity
            totalPrice += price * quantity;  // Accumulate the total price
        });
        cartTotalElement.textContent = '₱' + totalPrice.toFixed(2);  // Display total price with 2 decimal places
    }

    // Function to update the total quantity in the cart button
    function updateCartQuantity() {
        let totalQuantity = 0;  // Variable to accumulate the total quantity
        const cartItemElements = document.querySelectorAll('.cart-item');
        cartItemElements.forEach(item => {
            const quantity = parseInt(item.querySelector('input[type="number"]').value);
            totalQuantity += quantity;  // Accumulate the total quantity
        });
        document.getElementById('cartTotalQuantity').textContent = totalQuantity;  // Update the superscript
    }

    // Add an item to the cart
    const addToCartButtons = document.querySelectorAll('.add-to-cart');
    addToCartButtons.forEach(button => {
        button.addEventListener('click', () => {
            const name = button.getAttribute('data-name');
            const price = parseFloat(button.getAttribute('data-price'));
            const existingCartItem = cartItems.querySelector(`[data-name="${name}"]`);

            if (existingCartItem) {  // If the item is already in the cart
                const quantityElement = existingCartItem.querySelector('input[type="number"]');
                let quantity = parseInt(quantityElement.value);
                quantity++;  // Increment the quantity
                quantityElement.value = quantity;
            } else {  // If the item is new to the cart
                const item = `
                    <div class="cart-item" data-name="${name}">
                        <span>${name}</span>
                        <span>${price} PHP</span>
                        <input type="number" value="1" min="1">
                        <button class="remove-item">Remove</button>
                    </div>
                `;
                cartItems.insertAdjacentHTML('beforeend', item);  // Add the new item to the cart
            }

            updateCartTotal();  // Update the total price
            updateCartQuantity();  // Update the superscript
        });
    });

    // Handle cart item removals
    cartItems.addEventListener('click', (event) => {
        if (event.target.classList.contains('remove-item')) {
            const item = event.target.parentElement;
            item.remove();  // Remove the cart item
            updateCartTotal();  // Update the total price
            updateCartQuantity();  // Update the superscript
        }
    });

    // Handle input changes (quantity updates)
    cartItems.addEventListener('input', (event) => {
        if (event.target.tagName === 'INPUT') {
            let quantity = parseInt(event.target.value);
            if (quantity < 1) {
                event.target.value = 1;  // Set minimum quantity to 1
            }
            updateCartTotal();  // Update the total price
            updateCartQuantity();  // Update the superscript
        }
    });

    // Show/close sidebar
    cartButton.addEventListener('click', () => {
        sidebar.style.display = 'block';
    });

    sidebarClose.addEventListener('click', () => {
        sidebar.style.display = 'none';
    });

    updateCartTotal();  // Initialize the total price
    updateCartQuantity();  // Initialize the superscript
});


// JavaScript function to handle logout
function logout() {
    window.location.href = 'includes/logout.php';  // Redirect to logout script
}

// Functions to open/close login forms
function openBox(param) {
    document.getElementById(param).style.display = "block";
}

function closeBox(param) {
    document.getElementById(param).style.display = "none";
}
