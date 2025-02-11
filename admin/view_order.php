<?php
require 'header.php';
require_once '../include/db_connect.php'; // Database connection

// Check if the admin is logged in (important!)
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "Admin") {
    header("Location: index.php"); // Redirect non-admins to customer homepage
    exit;
}

// Check if the order ID is set and is a valid number
$order_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($order_id <= 0) {
    echo "Invalid Order ID.";
    exit;
}

// Function to fetch order details
function getOrderDetails($conn, $order_id) {
    $sql = "SELECT o.id, o.order_date, o.total_amount, o.status, u.username, u.email
            FROM orders o
            JOIN users u ON o.user_id = u.id
            WHERE o.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

// Function to fetch order items
function getOrderItems($conn, $order_id) {
    $order_items = [];
    $sql = "SELECT product_id, quantity, price, title, author, image FROM order_items WHERE order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $order_items[] = $row;
    }
    return $order_items;
}

// Fetch order details
$order = getOrderDetails($conn, $order_id);
if (!$order) {
    echo "Order not found.";
    exit;
}

// Fetch order items
$order_items = getOrderItems($conn, $order_id);

// Display the order details
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details</title>
    <link rel="icon" type="image/x-icon" href="../images/slogo.ico">

    <!-- font-awesome cdn link -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>

    <!-- custom css file link -->
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>

    <section id="page-header" class="about-header">
        <h2>Order Details</h2>
        <p>View detailed information about this order</p>
    </section>

    <div class="account-container">
        <section class="order-details">
            <h2>Order Information</h2>
                <p><strong>Order ID:</strong> <?php echo htmlspecialchars($order["id"]); ?></p>
                <p><strong>Customer Name:</strong> <?php echo htmlspecialchars($order["username"]); ?></p>
                <p><strong>Customer Email:</strong> <?php echo htmlspecialchars($order["email"]); ?></p>
                <p><strong>Order Date:</strong> <?php echo htmlspecialchars($order["order_date"]); ?></p>
                <p><strong>Total Amount:</strong> LKR <?php echo htmlspecialchars(number_format($order["total_amount"], 2)); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($order["status"]); ?></p>
         </section>
         <section class="order-details">
            <h2>Order Items</h2>
            <table>
                <thead>
                    <tr>
                        <th>Book Cover</th>
                        <th>Book Title</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
    <?php foreach ($order_items as $item): ?>
    <tr>
        <td>
            <center><img src="<?php echo "../". htmlspecialchars($item["image"]); ?>" alt="Book Cover" style="width: 100px;"></center>
        </td>
        <td><?php echo htmlspecialchars($item["title"]) . " - " . htmlspecialchars($item["author"]); ?></td>
        <td><?php echo htmlspecialchars($item["quantity"]); ?></td>
        <td>LKR <?php echo htmlspecialchars(number_format($item["price"], 2)); ?></td>
    </tr>
    <?php endforeach; ?>
</tbody>

            </table>
        </section>

    </div>

<?php
require 'footer.php';
//Check before connection close at the end
if (isset($conn)) {
   $conn->close();   // Close the connection at the end
}
?>
</body>
</html>