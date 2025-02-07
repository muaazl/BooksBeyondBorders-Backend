//pages
const pages = ["shop.php", "shop1.php", "shop2.php"];
const currentPage = window.location.pathname.split("/").pop(); // Get current page filename
const nextPage = pages[pages.indexOf(currentPage) + 1];
const prePage = pages[pages.indexOf(currentPage) - 1];

// Update the 'next' arrow button if a next page exists
if (nextPage) {
    document.getElementById("nextPage").href = nextPage;
}

if (prePage) {
    document.getElementById("prePage").href = prePage;
}

function toggleContent(contentId, link) {
  const content = document.getElementById(contentId);

  if (content.classList.contains("collapsed")) {
      content.classList.remove("collapsed"); // Remove the collapsed class
      link.textContent = "SHOW LESS"; // Change the button text
  } else {
      content.classList.add("collapsed"); // Add the collapsed class
      link.textContent = "CONTINUE READING"; // Revert the button text
  }
}

function showOverlay(img) {
    const overlay = document.getElementById('overlay');
    const overlayImg = document.getElementById('overlay-img');
    overlay.style.display = 'flex'; // Show overlay
    overlayImg.src = img.src; // Set overlay image to clicked image source
  }
  
  // Function to close the overlay when clicked
  function closeOverlay() {
    const overlay = document.getElementById('overlay');
    overlay.style.display = 'none'; // Hide overlay
  }


//cart
// Function to initialize cart count on page load
function initializeCartCount() {
    const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];
    const itemCount = cartItems.reduce((total, item) => total + item.quantity, 0);
    document.getElementById('cart-count').innerText = `(${itemCount})`;
}

//scrolltoTop
// Get the button
const scrollToTopBtn = document.getElementById("scrollToTopBtn");

// Show button when user scrolls down
window.onscroll = function() {
  toggleButtonVisibility();
};

function toggleButtonVisibility() {
  if (document.body.scrollTop > 300 || document.documentElement.scrollTop > 300) {
    scrollToTopBtn.classList.add('show');
    scrollToTopBtn.classList.remove('hide');
  } else {
    scrollToTopBtn.classList.add('hide');
    scrollToTopBtn.classList.remove('show');
  }
}

// Scroll smoothly to top when button is clicked
function scrollToTop() {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
}

//SEARCH SCRIPT
console.log(window.productData);

//search function begins here
const searchInput = document.getElementById("searchInput");
const clearButton = document.getElementById("clearButton");
const searchButton = document.getElementById("searchButton");
const searchResults = document.getElementById("searchResults");

// Display Results as Dropdown
function displayResults(results) {
    searchResults.innerHTML = ""; // Clear existing results

    if (results.length === 0) {
        searchResults.classList.remove('show'); // Collapse dropdown if no results
        return;
    }

    searchResults.classList.add('show'); // Show dropdown
    results.forEach((product) => {
        const li = document.createElement("li");

        // Product Details - ADJUST TO USE product.image, product.author, product.title
        li.innerHTML = `
            <img src="${product.image}" alt="${product.title}" />
            <div>
                <strong>${product.title}</strong> <br>
                <small>${product.author}</small> <br>
                <span>LKR ${product.price}</span>
            </div>
        `;

        // Navigate to Single Product Page
        li.addEventListener("click", () => {
            window.location.href = `product.php?id=${product.id}`;
        });

        searchResults.appendChild(li);
    });
}

// Search Functionality
function searchProducts(query) {
    // Use AJAX to fetch the results from PHP
    fetch('assets/search.php?query=' + encodeURIComponent(query)) // Corrected query parameter name
        .then(response => response.json())
        .then(data => {
            displayResults(data);
        })
        .catch(error => {
            console.error('Error:', error);
            searchResults.innerHTML = "<li>Error fetching search results.</li>";
            searchResults.classList.add('show');
        });
}

// Event Listeners
searchInput.addEventListener("input", (e) => {
    const query = e.target.value.trim();
    if (query) {
        searchProducts(query);
    } else {
        searchResults.classList.remove('show'); // Collapse dropdown if no query
    }
});

clearButton.addEventListener("click", () => {
    searchInput.value = "";
    searchResults.classList.remove('show'); // Collapse dropdown on clear
});

searchButton.addEventListener("click", () => {
    const query = searchInput.value.trim();
    if (query) {
        searchProducts(query);
    }
});

// Show dropdown when input field gains focus
searchInput.addEventListener("focus", () => {
    const query = searchInput.value.trim();
    if (query) {
        searchResults.classList.add('show'); // Expand dropdown
        searchProducts(query); // Search results for the current query
    } else {
        searchResults.classList.remove('show'); // Collapse dropdown if no query
    }
});

// Hide dropdown when input field loses focus
searchInput.addEventListener("blur", () => {
    setTimeout(() => {
        searchResults.classList.remove('show'); // Collapse dropdown after brief delay
    }, 100); // Timeout allows clicking on dropdown items
});

// Optional: Focus the input on clearing it, so the user can type again
clearButton.addEventListener("click", () => {
    searchInput.focus();
});