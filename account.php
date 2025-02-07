<?php
require_once 'include/header.php';
require_once 'include/db_connect.php';

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Get user ID from session
$user_id = $_SESSION["id"];

// Fetch user details from the database
$sql_user = "SELECT username, email FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$result_user = $stmt_user->get_result();

if ($result_user->num_rows == 1) {
    $row_user = $result_user->fetch_assoc();
    $username = htmlspecialchars($row_user["username"]);
    $email = htmlspecialchars($row_user["email"]);
} else {
    // Handle the case where the user is not found
    $username = "User Not Found";
    $email = "N/A";
}
$stmt_user->close();

// Fetch purchase history from the database
$sql_orders = "SELECT id, order_date, total_amount, status FROM orders WHERE user_id = ?";
$stmt_orders = $conn->prepare($sql_orders);
$stmt_orders->bind_param("i", $user_id);
$stmt_orders->execute();
$result_orders = $stmt_orders->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBB - User Account</title>
    <link rel="icon" type="image/x-icon" href="images/slogo.ico">

    <!-- font-awesome cdn link -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />

    <!-- custom css file link -->
    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

    <section id="page-header" class="about-header">
        <h2>#UserAccount</h2>
        <p>Update your personal information</p>
    </section>

    <div class="account-container">

        <!-- User Details Section -->
        <section class="user-details">
            <h2>Your Details</h2>
            <p>Name: <?php echo htmlspecialchars($username); ?></p>
            <p>Email: <?php echo htmlspecialchars($email); ?></p>
            <p>Password: <?php
                $password = htmlspecialchars($password);
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                echo $hashed_password; 
            ?></p>
            <!-- Add fields here for editing user details -->
        </section>

        <!-- Purchase History Section -->
        <section class="purchase-history">
            <h2>Purchase History</h2>
            <table>
                <tr>
                    <th>Order ID</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
                <?php if ($result_orders->num_rows > 0): ?>
                    <?php while ($row_orders = $result_orders->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row_orders["id"]); ?></td>
                            <td><?php echo htmlspecialchars($row_orders["order_date"]); ?></td>
                            <td>LKR <?php echo htmlspecialchars(number_format($row_orders["total_amount"], 2)); ?></td>
                            <td><?php echo htmlspecialchars($row_orders["status"]); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4">No purchase history found.</td>
                    </tr>
                <?php endif; ?>
            </table>
        </section>

        <!-- Order Tracking Section -->
        <section class="order-tracking">
            <h2>Order Tracking</h2>
            <form method="post">
                <input type="text" name="order_id" placeholder="Enter Order ID">
                <button type = "submit">Track Order</button>
            </form>

<?php
if(isset($_POST['order_id'])) {

    $order_id = intval($_POST['order_id']);

    // SQL query to retrieve the order details
    $sql_track = "SELECT id, order_date, total_amount, status FROM orders WHERE user_id = ? and id = ?";  // Prepared statement
    $stmt_track = $conn->prepare($sql_track);
    $stmt_track->bind_param("ii", $user_id, $order_id); // Bind the parameter (i = integer)
    $stmt_track->execute(); // Execute the query
    $result_track = $stmt_track->get_result(); // Get the result
}
?>
            <div class="tracking-result">
                <table>
                    <tr>
                        <th>Order ID</th>
                        <th><center>Product</center></th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>

                        <?php if (isset($result_track) && $result_track->num_rows > 0): ?>
                           <?php
                                while ($track = $result_track->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>".htmlspecialchars($track['id'])."</td>";
                                    echo "<td>".htmlspecialchars($track['total_amount'])."</td>";
                                    echo "<td>".htmlspecialchars($track['status'])."</td>";
                                    echo "<td>".htmlspecialchars($track['order_date'])."</td>";
                                    echo "<td></td>";
                                    echo "</tr>";
                                }
                           ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5">No order found with that ID.</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </section>

        <!-- Sign Out Button -->
        <a href="logout.php"> <button id="sign-out-btn">sign out</button> </a>
    </div>

    <?php
include 'include/footer.php';
//Close connection at the end to close it
if(isset($stmt_orders)){
    $stmt_orders->close();
}
if(isset($stmt_track)){
    $stmt_track->close();
}
if (isset($conn)) { // Check if the connection was established
    $conn->close();   // Close the connection at the end
}

?>

    <!-- javascript script file link -->
    <script src="assets/data.js"></script>
    <script src="assets/account.js"></script>
    <script src="assets/script.js"></script>
</body>

</html>