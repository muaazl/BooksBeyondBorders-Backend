<?php
    require_once 'include/db_connect.php';
if (isset($_GET['order_id'])) {
    // Handle AJAX request without including headers

    $order_id = intval($_GET['order_id']);

    $sql = "SELECT oi.quantity, CAST(oi.price AS DECIMAL(10, 2)) AS price, p.title, p.image, p.author
            FROM order_items oi
            JOIN products p ON oi.product_id = p.id
            WHERE oi.order_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $order_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $order_details = array();
    while ($row = $result->fetch_assoc()) {
        $order_details[] = $row;
    }

    header('Content-Type: application/json');
    echo json_encode($order_details);
    $stmt->close();
    $conn->close();
    exit(); // Stop further execution
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["action"]) && $_POST["action"] == "update_user_details") {
    session_start();
    // Get the user ID from the session
    if (!isset($_SESSION["id"])) {
        echo json_encode(array("status" => "error", "message" => "User not logged in."));
        exit;
    }
    $user_id = $_SESSION["id"];

    // Get the form data
    $username = trim($_POST["username"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);

    // Validate the form data
    if (empty($username)) {
        echo json_encode(array("status" => "error", "message" => "Please enter your username."));
        exit;
    }
    if (empty($email)) {
        echo json_encode(array("status" => "error", "message" => "Please enter your email."));
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(array("status" => "error", "message" => "Please enter a valid email address."));
        exit;
    }

    // Prepare an update statement
    $sql = "UPDATE users SET username = ?, email = ?";

    // Add password update if a new password is provided
    if (!empty($password)) {
        $sql .= ", password = ?";
    }

    $sql .= " WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        // Bind parameters
        if (!empty($password)) {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("sssi", $username, $email, $hashed_password, $user_id);
        } else {
            $stmt->bind_param("ssi", $username, $email, $user_id);
        }

        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            echo json_encode(array("status" => "success", "message" => "User details updated successfully."));
        } else {
            echo json_encode(array("status" => "error", "message" => "Something went wrong. Please try again later."));
        }

        // Close statement
        $stmt->close();
    } else {
        echo json_encode(array("status" => "error", "message" => "Failed to prepare statement"));
    }

    // Close connection
    $conn->close();
    exit; // Stop further execution
}

require_once 'include/header.php';

// Fetch user details from the database
$sql_user = "SELECT username, email FROM users WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);  // Use the $conn
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

// ... (Authentication and user details fetching code) ...
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
    <!-- ... (Your HTML body content) ... -->
    <!-- ... (Account details, purchase history, etc.) ... -->
    <section id="page-header" class="about-header">
        <h2>#UserAccount</h2>
        <p>Update your personal information</p>
    </section>

    <div class="account-container">

        <!-- User Details Section -->
        <section class="user-details">
            <h2>Your Details</h2>

            <!-- View Mode -->
            <div id="view-mode">
                <p>Name: <span id="name"><?php echo htmlspecialchars($username); ?></span></p>
                <p>Email: <span id="email"><?php echo htmlspecialchars($email); ?></span></p>
                <!-- Hidden password -->
                <p>Password: <span id="password">********</span></p>
                <button id="edit-btn">Edit</button>
            </div>

            <!-- Edit Mode (Hidden by default) -->
            <form id="edit-form" style="display: none;" method="post">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>">
                 <input type="hidden" name="action" value="update_user_details"> <!-- Hidden action field -->
                <p>Name: <input type="text" name="username" value="<?php echo htmlspecialchars($username); ?>"></p>
                <p>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>"></p>
                <p>New Password: <input type="password" name="password" placeholder="Enter new password"></p>

                <button type="submit">Save</button>
                <button type="button" id="cancel-btn">Cancel</button>
            </form>
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
                        <tr onclick="showOrderDetails(<?php echo htmlspecialchars($row_orders["id"]); ?>)">
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

?>

<script src="assets/data.js"></script>
    <script src="assets/account.js"></script>
    <script src="assets/script.js"></script>
    <script>
    function showOrderDetails(orderId) {
        // Check if the order details are already displayed
        const existingDetails = document.querySelector(`.order-details[data-order-id="${orderId}"]`);
    
        if (existingDetails) {
            // If details are already visible, remove them
            existingDetails.remove();
            return; // Exit the function
        }
    
        // Remove any other order details that might be open
        const allDetails = document.querySelectorAll('.order-details');
        allDetails.forEach(detail => detail.remove());
    
        // Fetch and display the order details
        fetch('account.php?order_id=' + orderId)
            .then(response => {
                if (!response.ok) throw new Error('Network error: ' + response.statusText);
                return response.json();
            })
            .then(data => {
                console.log(data); // Debug: Inspect the data structure
                if (data.length === 0) {
                    alert('No details found for this order.');
                    return;
                }
    
                // Create the order details div
                const orderDetailsDiv = document.createElement('div');
                orderDetailsDiv.className = 'order-details';
                orderDetailsDiv.setAttribute('data-order-id', orderId); // Add a data attribute to identify the order
                orderDetailsDiv.innerHTML = `
                    <h3>Order #${orderId} Details</h3>
                    <table>
                    <tr>
                        <th>Book Cover</th>
                        <th>Book Title</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                    ${data.map(item => `
                        <tr>
                            <td><center><img src="${item.image}" alt="${item.title}" width="100"></center></td>
                            <td>${item.title} - ${item.author}</td>
                            <td>${item.quantity}</td>
                            <td>LKR ${parseFloat(item.price).toFixed(2)}</td>
                        </tr>
                    `).join('')}
                </table>
                `;
    
                // Insert after the purchase history section
                const purchaseHistory = document.querySelector('.purchase-history');
                purchaseHistory.insertAdjacentElement('afterend', orderDetailsDiv);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to load order details. Check the console for more information.');
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
    const editBtn = document.getElementById('edit-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const editForm = document.getElementById('edit-form');
    const viewMode = document.getElementById('view-mode');

    editBtn.addEventListener('click', function() {
        viewMode.style.display = 'none';
        editForm.style.display = 'block';
    });

    cancelBtn.addEventListener('click', function() {
        editForm.style.display = 'none';
        viewMode.style.display = 'block';
    });

    // AJAX form submission
    editForm.addEventListener('submit', function(event) {
    event.preventDefault(); // Prevent the default form submission

    const formData = new FormData(editForm); // Create a FormData object from the form
    formData.append('action', 'update_user_details'); // Add action identifier

    fetch('account.php', {  // Same file!
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log(data); // Debug: Inspect the server response
        if (data.status === 'success') {
            // Update the displayed user details
            document.getElementById('name').textContent = formData.get('username');
            document.getElementById('email').textContent = formData.get('email');
            // Potentially update the displayed password (more complex - see notes below)

            // Switch back to view mode
            editForm.style.display = 'none';
            viewMode.style.display = 'block';

            alert('User details updated successfully!');
        } else {
            alert('Failed to update user details: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred during the update.');
    });
});
});
    </script>
    
</body>
</html>
<?php
if (isset($conn)) { // Check if the connection was established
    $conn->close();   // Close the connection at the end
}
?>