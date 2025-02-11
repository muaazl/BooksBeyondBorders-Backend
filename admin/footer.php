<?php
// footer.php
?>

<footer class="section-p1">
    <div class="col">
        <img class="logo" src="../images/logo.png" alt="">
        <h4>Contact</h4>
        <p><strong>Phone:</strong> +94-076-1164425</p>
        <p><strong>Hours:</strong> 10:00 - 18:00, Mon - Sat</p>
        <div class="follow">
            <h4>Follow us</h4>
            <div class="icon">
                <i class="fab fa-facebook-f"></i>
                <i class="fab fa-twitter"></i>
                <i class="fab fa-instagram"></i>
                <i class="fab fa-pinterest-p"></i>
                <i class="fab fa-youtube"></i>
            </div>
        </div>
    </div>
    <div class="col">
        <h4>About</h4>
        <a href="../about.php">About us</a>
        <a href="#">Return Policy</a>
        <a href="#">Privacy Policy</a>
        <a href="#">Terms & Conditions</a>
        <a href="../about.php">Contact Us</a>
    </div>
    <div class="col">
        <h4>My Account</h4>
        <?php
            $isLoggedIn = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;
        ?>
        <a href="<?php echo $isLoggedIn ? '../logout.php' : '../signup.php'; ?>"><?php echo $isLoggedIn ? 'Sign Out' : 'Sign In'; ?></a>
        <a href="../cart.php">View Cart</a>
        <a href="#">My Wishlist</a>
        <a href="#">Track My Order</a>
        <a href="#">Help</a>
    </div>
    <div class="col install">
        <h4>Payment Policy</h4>
        <p>Secured Payment Gateway</p>
        <img src="../images/pay/pay.png" alt="">
    </div>
    <div class="copyright">
        <p>Books Beyond Borders | All rights reserved | © 2024</p>
    </div>
</footer>