<?php
session_start(); // Start the session
require_once '../include/db_connect.php'; // Include database connection

// Function to get the books count
function getBooksCount($conn) {
    $count = 0;
    $sql_books = "SELECT COUNT(*) AS total_books FROM products";
    $stmt_books = $conn->prepare($sql_books);
    
    if ($stmt_books) {
        $stmt_books->execute();
        $result_books = $stmt_books->get_result();
        if ($result_books->num_rows > 0) {
            $row_books = $result_books->fetch_assoc();
            $count = (int)$row_books["total_books"];
        }
        $stmt_books->close();
    }
    return $count;
}

// Function to get the orders count
function getOrdersCount($conn) {
    $count = 0;
    $sql_orders = "SELECT COUNT(*) AS total_orders FROM orders";
    $stmt_orders = $conn->prepare($sql_orders);
    
    if ($stmt_orders) {
        $stmt_orders->execute();
        $result_orders = $stmt_orders->get_result();
        if ($result_orders->num_rows > 0) {
            $row_orders = $result_orders->fetch_assoc();
            $count = (int)$row_orders["total_orders"];
        }
        $stmt_orders->close();
    }
    return $count;
}

function getUsersCount($conn) {
    $count = 0;
    $sql_users = "SELECT COUNT(*) AS total_users FROM users";
    $stmt_users = $conn->prepare($sql_users);
    
    if ($stmt_users) {
        $stmt_users->execute();
        $result_users = $stmt_users->get_result();
        if ($result_users->num_rows > 0) {
            $row_users = $result_users->fetch_assoc();
            $count = (int)$row_users["total_users"];
        }
        $stmt_users->close();
    }
    return $count;
}

// Check login status
$isLoggedIn = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true;

// Get counts
$orders_count = getOrdersCount($conn);
$users_count = getUsersCount($conn);
$books_count = getBooksCount($conn);

// Get the current page filename
$current_page = basename($_SERVER['PHP_SELF']);

// Function to determine if a link is active
function isActive($page, $current) {
    return ($page == $current) ? 'active' : '';
}
?>

<section id="header">
    <a href="index.php"><img src="../images/logo.png" class="logo" alt=""></a>
    <div>
        <ul id="navbar">
            <li><a class="<?php echo isActive('index.php', $current_page); ?>" href="index.php">Home</a></li>
            <li><a class="<?php echo isActive('orders.php', $current_page); ?>" href="orders.php">Orders <span id="orders-count">(<?php echo htmlspecialchars((string)$orders_count, ENT_QUOTES, 'UTF-8'); ?>)</span></a></li>
            <li><a class="<?php echo isActive('users.php', $current_page); ?>" href="users.php">Users <span id="users-count">(<?php echo htmlspecialchars((string)$users_count, ENT_QUOTES, 'UTF-8'); ?>)</span></a></li>
            <li><a class="<?php echo isActive('books.php', $current_page); ?>" href="books.php">Books <span id="books-count">(<?php echo htmlspecialchars((string)$books_count, ENT_QUOTES, 'UTF-8'); ?>)</span></a></li>
            <?php if ($isLoggedIn): ?>
                <li><a href="../logout.php">Sign Out</a></li>
            <?php endif; ?>
        </ul>
    </div>
</section>
