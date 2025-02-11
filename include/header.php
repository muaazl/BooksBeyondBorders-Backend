<?php
session_start(); // Start the session (if not already started)
require_once 'db_connect.php'; // Include database connection

// Function to get the cart count from the database
function getCartCount($conn, $user_id)
{
    $count = 0;
    if ($user_id > 0) {
        // Prepare the SQL statement to get the cart ID for the user
        $sql_cart = "SELECT id FROM carts WHERE user_id = ?";
        $stmt_cart = $conn->prepare($sql_cart);

        if ($stmt_cart) {
            $stmt_cart->bind_param("i", $user_id);
            $stmt_cart->execute();
            $result_cart = $stmt_cart->get_result();

            if ($result_cart->num_rows > 0) {
                $row_cart = $result_cart->fetch_assoc();
                $cart_id = $row_cart["id"];

                // Prepare the SQL statement to get the total items from the cart
                $sql_count = "SELECT SUM(quantity) AS total_items FROM cart_items WHERE cart_id = ?";
                $stmt_count = $conn->prepare($sql_count);

                if ($stmt_count) {
                    $stmt_count->bind_param("i", $cart_id);
                    $stmt_count->execute();
                    $result_count = $stmt_count->get_result();

                    if ($result_count->num_rows > 0) {
                        $row_count = $result_count->fetch_assoc();
                        //To prevent SQL injection if the row is null it will be 0 and has value
                        $count = (int)$row_count["total_items"];
                    }
                     if(isset($stmt_count)){$stmt_count->close();}
                } else {
                    echo "Error preparing count statement: " . $conn->error;
                }
            }

        } else {
            echo "Error preparing cart statement: " . $conn->error;
        }
         if(isset($stmt_cart)){$stmt_cart->close();}
    }
    return $count;
}
// Check to determine what account used first before rendering all page or can cause issues.
$isLoggedIn = false;
$user_id = 0;
if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
    $isLoggedIn = true;
    $user_id = $_SESSION["id"];
}

// Get the cart count for the current user
$cart_count = getCartCount($conn, $user_id);

// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);

// Function to determine if a link is active
function isActive($page, $current)
{
    return ($page == $current) ? 'active' : '';
}

?>

<section id="header">
    <a href="index.php"><img src="images/logo.png" class="logo" alt=""></a>
    <div>
        <ul id="navbar">
            <li><a class="<?php echo isActive('index.php', $current_page); ?>" href="index.php">Home</a></li>
            <li><a class="<?php echo isActive('shop.php', $current_page); ?>" href="shop.php">Catalog</a></li>
            <li><a class="<?php echo isActive('cart.php', $current_page); ?>" href="cart.php">Cart <span id="cart-count">(<?php echo htmlspecialchars((string)$cart_count, ENT_QUOTES, 'UTF-8'); ?>)</span></a></li>
            <li><a class="<?php echo isActive('blog.php', $current_page); ?>" href="blog.php">Blog</a></li>
            <li><a class="<?php echo isActive('about.php', $current_page); ?>" href="about.php">About</a></li>

            <?php if ($isLoggedIn): ?>
                <li><a class="<?php echo isActive('account.php', $current_page); ?>" href="account.php">Account</a></li>
                <li><a href="logout.php">Sign Out</a></li>
            <?php else: ?>
                <li><a class="<?php echo isActive('login.php', $current_page); ?>" href="login.php">Sign In</a></li>
                <li><a class="<?php echo isActive('signup.php', $current_page); ?>" href="signup.php">Sign Up</a></li>
            <?php endif; ?>

            <a href="#" id="close"><i class="far fa-times"></i></a>
        </ul>
    </div>
</section>