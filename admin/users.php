<?php
require 'header.php';
require_once '../include/db_connect.php';

// Check if the admin is logged in and has the correct role
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "Admin") {
    header("Location: index.php"); // Redirect non-admins to customer homepage
    exit;
}
// Functions
function getUsers($conn, $search_term="", $sort_column = 'id', $sort_direction = 'ASC', $start_index = 0, $items_per_page = 10){
  $users = [];
  $sql = "SELECT id, username, email, role FROM users WHERE (username LIKE '%$search_term%' OR email LIKE '%$search_term%' OR role LIKE '%$search_term%')";
  $sql .= " ORDER BY $sort_column $sort_direction";
  $sql .= " LIMIT $start_index, $items_per_page";
 $result = $conn->query($sql);
     if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
               $users[] = $row;
          }
     }
    return $users;
}

function getTotalUsersCount($conn, $search_term="") {
        $sql = "SELECT COUNT(id) AS total FROM users WHERE (username LIKE '%$search_term%' OR email LIKE '%$search_term%' OR role LIKE '%$search_term%')";
        $result = $conn->query($sql);
        $row = $result->fetch_assoc();
        return $row['total'];

}

function getOrdersPlacedCount($conn, $user_id) {
    $sql = "SELECT COUNT(id) AS total_orders FROM orders WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row['total_orders'];
    } else {
        return 0; // Default case for no orders
    }

    $stmt->close();
}

// Handle form submission for user edits and deletes
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["edit_user"])) {
        $user_id = $_POST["user_id"];
        $new_role = $_POST["new_role"];
        $sql_update = "UPDATE users SET role = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $new_role, $user_id);
        $stmt_update->execute();
        $stmt_update->close();
    }
    // Code for Delete should exist for preventing security bugs by outside users.
    elseif (isset($_POST["delete_user"])) {
       $user_id = $_POST["user_id"];
        $sql_delete = "DELETE FROM users WHERE id = ?";
         $stmt_delete = $conn->prepare($sql_delete);

           if($stmt_delete){
             $stmt_delete->bind_param("i", $user_id);
             $stmt_delete->execute();
             $stmt_delete->close();
           }
          else {
            echo "There was an error deleting a user. Report to admin";
          }

    }
}

// Pagination and other parameter set
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sort_direction = isset($_GET['direction']) ? $_GET['direction'] : 'ASC';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

$items_per_page = 10;
$start_index = ($page - 1) * $items_per_page;

$users = getUsers($conn, $search_term, $sort_column, $sort_direction, $start_index, $items_per_page);
$total_users = getTotalUsersCount($conn, $search_term);

$totalPages = ceil($total_users / $items_per_page);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBB - Admin Users</title>
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
</style>
</head>
<body>

<div class="account-container">
<div class="order-details">
        <h2>Users</h2>
        <form method="GET">
        Search Users: <input type="text" name="search" value="<?php echo htmlspecialchars($search_term); ?>">
        <button type="submit">Apply</button>

        </form>

    <table id="users-table" width="100%">
        <thead>
            <tr>
                <th><a href="?sort=id&direction=<?php echo ($sort_column == 'id' && $sort_direction == 'ASC') ? 'DESC' : 'ASC'; ?>">User ID</a></th>
                <th><a href="?sort=username&direction=<?php echo ($sort_column == 'username' && $sort_direction == 'ASC') ? 'DESC' : 'ASC'; ?>">Name</a></th>
                <th><a href="?sort=email&direction=<?php echo ($sort_column == 'email' && $sort_direction == 'ASC') ? 'DESC' : 'ASC'; ?>">Email</a></th>
                <th><a href="?sort=role&direction=<?php echo ($sort_column == 'role' && $sort_direction == 'ASC') ? 'DESC' : 'ASC'; ?>">Role</a></th>
                <th>Orders Placed</th>
                <th>Actions</th>
            </tr>
        </thead>
       <tbody>
    <?php if (empty($users)): ?>
        <tr>
            <td colspan="6">No users found.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($users as $user): ?>
            <tr>
                <td><?php echo htmlspecialchars($user["id"]); ?></td>
                <td><?php echo htmlspecialchars($user["username"]); ?></td>
                <td><?php echo htmlspecialchars($user["email"]); ?></td>
                <td>
                    <form method="post">
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user["id"]); ?>">
                        <select name="new_role" onchange="this.form.submit()">
                            <option value="Customer" <?php if($user["role"] == 'Customer') echo 'selected'; ?>>Customer</option>
                            <option value="Admin" <?php if($user["role"] == 'Admin') echo 'selected'; ?>>Admin</option>
                        <input type="hidden" name="edit_user">

                    </form>
                </td>
                <td><?php echo htmlspecialchars(getOrdersPlacedCount($conn, $user["id"])); ?></td>

                 <td class = "actions">
                                <form method="post">
                                    <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user["id"]); ?>">
                                    <button type="submit" name="delete_user">Delete</button>
                                </form>
                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>
</tbody>
</table>
        </div>
</div>
<?php include 'footer.php';
//Check to end connection
if (isset($conn)) { // Check if the connection was established
    $conn->close();   // Close the connection at the end
}

?>

</body>
</html>