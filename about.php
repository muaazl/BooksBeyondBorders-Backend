<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBB - About</title>
    <link rel="icon" type="image/x-icon" href="images/slogo.ico">

    <!-- font-awesome cdn link -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />

    <!-- custom css file link -->
    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

<?php
include 'include/header.php';
?>

    <section id="page-header" class="about-header">
        <h2>#OurStoryTogether</h2>
        <p>Learn More About Us or Drop Us a Message – We’d Love to Hear from You!</p>
    </section>

    <section id="about-head" class="section-p1">
        <img src="images/about/a6.jpg" alt="Book Store">
        <div>
            <h2>About Us</h2>
            <p>
                At Books Beyond Borders, we’re passionate about bringing books directly to your doorstep. Whether you’re an avid reader or just getting started, we provide an extensive collection of books across various genres to cater to every taste. Our mission is simple: to offer high-quality books at affordable prices, making reading more accessible to everyone.
            </p>
            <p>
                With carefully selected books and an easy-to-navigate online store, we ensure that you’ll always find the perfect book for your next adventure, whether it’s fiction, non-fiction, or something in between. 
            </p>
            <p>
                As a small business run by a single person, I take pride in offering personalized customer service and ensuring that each order is handled with care. I’m here to help you discover new books, and I’m committed to making your shopping experience as smooth as possible.
            </p>
            <p>
                Browse our catalog today and start your journey with a book that sparks your imagination.
            </p>
            <abbr title="Your next favorite book is just a click away.">Discover your next great read now!</abbr>
            <br><br>
            <marquee bgcolor="#ccc" loop="-1" scrollamount="5" width="100%">
                Affordable books for every reader. Find your next read today at Books Beyond Borders.
            </marquee>
        </div>
    </section>
    
    <section id="reviews" class="section-p1">
        <div class="slider-container">
            <div class="slider">
                <div class="review-box">
                    <img id="profile" src="images/people/1.jpg" alt="">
                    <h6>Excellent Service!</h6>
                    <p>"Customer service was so helpful. They resolved my query instantly!"</p>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <div class="review-box">
                    <img id="profile" src="images/people/2.jpg" alt="">
                    <h6>Great Packaging!</h6>
                    <p>"Books were packaged securely and arrived in mint condition. Very impressed!"</p>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <div class="review-box">
                    <img id="profile" src="images/people/3.jpg" alt="">
                    <h6>Highly Recommend!</h6>
                    <p>"Amazing collection of books! Found titles I couldn’t find anywhere else."</p>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>
                <div class="review-box">
                    <img id="profile" src="images/people/4.jpg" alt="">
                    <h6>Great Prices!</h6>
                    <p>"Got the best deals on books. Excellent value for money."</p>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <div class="review-box">
                    <img id="profile" src="images/people/5.jpg" alt="">
                    <h6>Fast Shipping!</h6>
                    <p>"Books arrived earlier than expected. Will definitely order again!"</p>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                    </div>
                </div>
                <div class="review-box">
                    <img id="profile" src="images/people/6.jpg" alt="">
                    <h6>Rare Finds!</h6>
                    <p>"Discovered unique editions I’ve been searching for ages. So happy!"</p>
                    <div class="star">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star-half-alt"></i>
                    </div>
                </div>                          
                <!-- Add more review boxes -->
            </div>
        </div>
    </section>
    <script>
        // Select the slider container and items
const slider = document.querySelector('.slider');
const reviewBoxes = document.querySelectorAll('.review-box');

// Get the width of each review box (including gap)
const boxWidth = reviewBoxes[0].offsetWidth + 30; // Adjust with your gap size
let currentIndex = 0; // Track the current visible slide index

// Function to slide the reviews
function slideReviews() {
    currentIndex++; // Move to the next item
    const totalBoxes = reviewBoxes.length;

    // Check if we've reached the end of the slider
    if (currentIndex >= totalBoxes) {
        slider.style.transition = 'none'; // Disable animation for reset
        slider.style.transform = 'translateX(0)'; // Reset to the beginning
        currentIndex = 0; // Reset index
        setTimeout(() => {
            slider.style.transition = 'transform 0.5s ease-in-out'; // Re-enable animation
            slideReviews(); // Start sliding again
        }, 500); // Pause before restarting
        return;
    }

    // Slide the slider to the next item
    slider.style.transform = `translateX(-${currentIndex * boxWidth}px)`;
}

// Duplicate review boxes for seamless looping
reviewBoxes.forEach((box) => {
    const clone = box.cloneNode(true); // Clone each review box
    slider.appendChild(clone); // Append clone to the slider
});

// Automatically slide every 5 seconds
setInterval(slideReviews, 3000); // Adjust the interval time as needed

    </script>
    

    <section id="form-details">
        <form action="">
            <span>LEAVE A MESSAGE</span>
            <h2>We love to hear from you</h2>
            <input type="text" placeholder="Your Name">
            <input type="text" placeholder="E-mail">
            <input type="text" placeholder="Subject">
            <textarea name="" id="" cols="30" rows="10" placeholder="Your Message"></textarea>
            <button class="normal">submit</button>
        </form>
        <div class="people">
            <div><style>#profile {border-radius: 50%;}</style>
                <img id="profile" src="images/people/1.jpg" alt="">
                <p><span>Muaaz Lattif</span> Web Developer<br>Phone: +94-076-1164425<br>Email: muaazlattif@gmail.com</p>
            </div>
            <div>
                <img id="profile" src="images/people/4.jpg" alt="">
                <p><span>Linkin Park</span> Quality Assurance<br>Phone: +94-123-4567890<br>Email: contact@example.com</p>
            </div>
            <div>
                <img id="profile" src="images/people/6.jpg" alt="">
                <p><span>Slim Shady</span> Marketing Manager<br>Phone: +94-123-4567890<br>Email: contact@example.com</p>
            </div>
        </div>
    </section>

    <?php
include 'include/footer.php';
?>

    <!-- javascript script file link -->
    <script src="assets/script.js" defer></script>
    <script>
        window.onload = function() {
            initializeCartCount(); }
            </script>
</body>

</html>