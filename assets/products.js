console.log(window.productData);
// Function to get product ID from URL
function getProductIDFromURL() {
const params = new URLSearchParams(window.location.search);
return params.get('id'); // expects URL like ?id=1
}

// Function to load product details based on the ID
function loadProductDetails() {
const productId = getProductIDFromURL();
const product = productData.find(item => item.id === productId);

if (product) {
    document.getElementById('MainImg').src = product.preview;
    document.getElementById('product-author').innerText = product.brand;
    document.getElementById('product-name').innerText = product.name;
    document.getElementById('product-price').innerText = `LKR ${product.price}`;
    document.getElementById('product-description').innerText = product.description;
} else {
    // Handle case where product is not found
    document.getElementById('productdetails').innerHTML = "<p>Product not found.</p>";
}
}

// Call the function to load product details when the page loads
window.onload = loadProductDetails;

function addToCart(productId) {
const product = productData.find(p => p.id === productId);
const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
const existingItem = cartItems.find(item => item.id === productId);

if (existingItem) {
    existingItem.quantity += 1; // Increase quantity if already in cart
} else {
    cartItems.push({
        id: product.id,
        title: product.name,
        author: product.brand,
        price: parseFloat(product.price),
        image: product.preview,
        quantity: 1 // default quantity when adding a new item
    });
}

localStorage.setItem('cartItems', JSON.stringify(cartItems));
alert(`${product.name} by ${product.brand} added to cart!`);
}

// Function to call addToCart with the correct product ID
function callAddtoCart() {
const productId = getProductIDFromURL();
addToCart(productId);
}

//cart
// Function to initialize cart count on page load
function initializeCartCount() {
const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
const itemCount = cartItems.reduce((total, item) => total + item.quantity, 0);
document.getElementById('cart-count').innerText = `(${itemCount})`;
}

initializeCartCount();
updateCart();