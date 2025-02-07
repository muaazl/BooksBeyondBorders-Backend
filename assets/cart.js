let cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
let totalPrice = 0.0; // Initialize totalPrice

// Update cart on page load
updateCart();

function updateCartCount() {
    const cartCountElement = document.getElementById('cart-count');
    const itemCount = cartItems.reduce((total, item) => total + item.quantity, 0); // Calculate total quantity
    cartCountElement.innerText = `(${itemCount})`; // Update cart count display
}

// Function to update cart and populate table
function updateCart() {
    const cartBody = document.getElementById('cart-body');
    const totalElement = document.getElementById('total-price'); // Assuming you have an element for total
    const subtotalElement = document.getElementById('cart-subtotal'); // Get the subtotal element

    if (cartBody) {
        cartBody.innerHTML = ''; // Clear the table before repopulating

        totalPrice = 0; // Reset totalPrice for recalculation

        if (cartItems.length === 0) {
            cartBody.innerHTML = '<tr><td colspan="6">No items in cart.</td></tr>';
        } else {
            cartItems.forEach((item, index) => {
                const subtotal = (item.price * item.quantity).toFixed(2);
                totalPrice += item.price * item.quantity; // Calculate totalPrice
                cartBody.innerHTML += `
                    <tr>
                        <td><i class="fas fa-times-circle remove-item" style="color:black" data-index="${index}"></i></td>
                        <td><img src="${item.image}" alt="${item.title}" width="50"></td>
                        <td>${item.title} - ${item.author}</td>
                        <td>LKR ${item.price.toFixed(2)}</td>
                        <td>
                            <input type="number" value="${item.quantity}" min="1" data-index="${index}" class="quantity-input">
                        </td>
                        <td>LKR ${subtotal}</td>
                    </tr>`;
            });
        }

        // Store updated cart in localStorage
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
        localStorage.setItem('totalPrice', totalPrice.toFixed(2));

        attachCartEventListeners();
        totalElement.innerText = `LKR ${totalPrice.toFixed(2)}`;
        subtotalElement.innerText = `LKR ${totalPrice.toFixed(2)}`;

        updateCartCount();
    }
}

// Function to update quantity
function updateQuantity(index) {
    const input = document.querySelector(`.quantity-input[data-index="${index}"]`);
    const newQuantity = parseInt(input.value);

    if (newQuantity > 0) {
        const quantityDifference = newQuantity - cartItems[index].quantity;
        cartItems[index].quantity = newQuantity;
        totalPrice += quantityDifference * cartItems[index].price; // Update totalPrice
    } else {
        removeCartItem(index);
    }

    updateCart();
}

// Function to attach event listeners for quantity inputs and remove item buttons
function attachCartEventListeners() {
    // Quantity input change event
    document.querySelectorAll('.quantity-input').forEach(input => {
        const index = input.getAttribute('data-index');
        input.addEventListener('change', function() {
            updateQuantity(index); // Call updateQuantity function
        });
    });

    // Remove item from cart
    document.querySelectorAll('.remove-item').forEach(button => {
        button.addEventListener('click', function() {
            const index = this.getAttribute('data-index');
            removeCartItem(index);
            updateCart();
        });
    });
}

// Function to remove item from cart
function removeCartItem(index) {
    totalPrice -= cartItems[index].price * cartItems[index].quantity; // Adjust totalPrice
    cartItems.splice(index, 1); // Remove item from cart
}

// Checkout functionality
function checkOut() {
    if (cartItems.length === 0) {
        alert('Your cart is empty! Please add items to the cart.');
    } else {
        alert(`Your total is LKR ${totalPrice.toFixed(2)}. Thank you for your purchase!`);
        
        // Clear cart
        cartItems = []; 
        totalPrice = 0.0; 
        
        // Update local storage to reflect empty cart
        localStorage.setItem('cartItems', JSON.stringify(cartItems));
        localStorage.setItem('totalPrice', totalPrice.toFixed(2));
        
        updateCart(); // Update UI
    }
}
