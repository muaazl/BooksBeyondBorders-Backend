<?php

require '../include/db_connect.php';
require 'header.php';
// Check if the admin is logged in (very important!)
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "Admin") {
    header("Location: index.php"); // Redirect non-admins to customer homepage
    exit;
}

// Function to fetch orders from the database
function getOrders($conn, $search_term="", $filter_status = "", $sort_column = 'order_date', $sort_direction = 'DESC', $start_index = 0, $items_per_page = 10){
     $orders = [];
     $sql = "SELECT o.id, o.order_date, o.total_amount, o.status, u.username
                 FROM orders o
                 JOIN users u ON o.user_id = u.id
                WHERE (u.username LIKE '%$search_term%' OR o.id LIKE '%$search_term%')"; // Join user name into user id so have all names to use from

        if (!empty($filter_status)) {
             $sql .= " AND o.status = '$filter_status'"; // Sanitize here already - be careful!
       }

         $sql .= " ORDER BY $sort_column $sort_direction";
         $sql .= " LIMIT $start_index, $items_per_page"; //Add those last part.
           $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
              $orders[] = $row;
            }

        }
             return $orders;

}

//Number and Page is also test to for it so to load that.

function getTotalOrdersCount($conn, $search_term="", $filter_status = "") {
      $sql = "SELECT COUNT(o.id) AS total FROM orders o JOIN users u ON o.user_id = u.id WHERE (u.username LIKE '%$search_term%' OR o.id LIKE '%$search_term%')";
        if (!empty($filter_status)) {
            $sql .= " AND o.status = '$filter_status'";
        }
         $result = $conn->query($sql);

          $row = $result->fetch_assoc();

          return $row['total'];

}

// Handle form submission to update order status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["update_status"])) {
    $order_id = $_POST["order_id"];
    $new_status = $_POST["new_status"];

    // Update order status in the database
    $sql_update = "UPDATE orders SET status = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);

    if ($stmt_update) {
        $stmt_update->bind_param("si", $new_status, $order_id);
        $stmt_update->execute();
        $stmt_update->close();
    }
}

$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$filter_status = isset($_GET['filter_status']) ? $_GET['filter_status'] : '';
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'order_date';
$sort_direction = isset($_GET['direction']) ? $_GET['direction'] : 'DESC';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$items_per_page = 5;
$start_index = ($page - 1) * $items_per_page;

$orders = getOrders($conn,$search_term, $filter_status, $sort_column, $sort_direction, $start_index, $items_per_page);
$total_orders = getTotalOrdersCount($conn, $search_term, $filter_status);

$totalPages = ceil($total_orders / $items_per_page);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBB - Admin Orders</title>
    <link rel="icon" type="image/x-icon" href="../images/slogo.ico">

    <!-- font-awesome cdn link -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>

    <!-- custom css file link -->
    <link rel="stylesheet" href="../assets/style.css">
    
    <style>
        .view {
            background-color: #088178;
            color: white;
            padding: 7px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button {
            background-color: #088178;
            color: white;
            padding: 7px 12px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        input {
            padding: 3px 7px;
            border: 2px solid #088178;
            border-radius: 5px;
        }
        select {
    padding: 3px 7px;
    font-size: 14px;
    border: 2px solid #088178;
    border-radius: 5px;
    background-color: #e1e1e1;
    color: #088178;
    font-weight: bold;
    cursor: pointer;
    outline: none;
    transition: all 0.3s ease;
}

/* When the dropdown is focused */
select:focus {
    border-color: #066c63;
    box-shadow: 0 0 5px rgba(8, 129, 120, 0.5);
}

select option {
    background: white;
    color: #088178;
    font-weight: bold;
}

        </style>
</head>
<body>

<div class="account-container">
    <div class="order-details">
<h2>Orders</h2>

<form method="GET">
    Search Order : <input type="text" name="search" value="<?php echo htmlspecialchars($search_term); ?>">
    Filter By Status:
        <select name="filter_status">
            <option value="">All</option>
            <option value="Pending" <?php if($filter_status == 'Pending') echo 'selected'; ?>>Pending</option>
            <option value="Shipped" <?php if($filter_status == 'Shipped') echo 'selected'; ?>>Shipped</option>
            <option value="Delivered" <?php if($filter_status == 'Delivered') echo 'selected'; ?>>Delivered</option>
            <option value="Canceled" <?php if($filter_status == 'Canceled') echo 'selected'; ?>>Canceled</option>
        </select>
    <button type="submit">Apply</button>
</form>

<table id="orders-table" width="100%">
    <thead>
        <tr>
            <th><a href="?sort=id&direction=<?php echo ($sort_column == 'id' && $sort_direction == 'ASC') ? 'DESC' : 'ASC'; ?>">Order ID</a></th>
            <th>Customer Name</th>
            <th><a href="?sort=order_date&direction=<?php echo ($sort_column == 'order_date' && $sort_direction == 'ASC') ? 'DESC' : 'ASC'; ?>">Date</a></th>
            <th><a href="?sort=total_amount&direction=<?php echo ($sort_column == 'total_amount' && $sort_direction == 'ASC') ? 'DESC' : 'ASC'; ?>">Total Amount</a></th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
<?php if (empty($orders)): ?>
        <tr>
            <td colspan="6">No order was Found.</td>
        </tr>
        <?php else: ?>
            <?php foreach ($orders as $order): ?>
        <tr>
            <td><?php echo htmlspecialchars($order["id"]); ?></td>
            <td><?php echo htmlspecialchars($order["username"])?></td>
            <td><?php echo htmlspecialchars($order["order_date"]); ?></td>
            <td>LKR <?php echo htmlspecialchars(number_format($order["total_amount"], 2)); ?></td>
            <td>
                <form method="post">
                    <input type="hidden" name="order_id" value="<?php echo htmlspecialchars($order["id"]); ?>">
                   <select name="new_status" onchange="this.form.submit()">
                      <option value="Pending" <?php if($order["status"] == 'Pending') echo 'selected'; ?>>Pending</option>
                      <option value="Shipped" <?php if($order["status"] == 'Shipped') echo 'selected'; ?>>Shipped</option>
                      <option value="Delivered" <?php if($order["status"] == 'Delivered') echo 'selected'; ?>>Delivered</option>
                      <option value="Canceled" <?php if($order["status"] == 'Canceled') echo 'selected'; ?>>Canceled</option>

                     <input type="hidden" name="update_status">
                   </select>
                </form>
            </td>
            <td class = "actions">
               <button><a href="view_order.php?id=<?php echo htmlspecialchars($order["id"]); ?>" class = "view">View</a></button>
            </td>
        </tr>
         <?php endforeach; ?>
            <?php endif; ?>
    </tbody>
</table>
</div>
</div>
<?php include 'footer.php';
//Close connection at the end to close it

if (isset($conn)) { // Check if the connection was established
   $conn->close();   // Close the connection at the end
}
?>

</body>
</html>