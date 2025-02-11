<?php
require 'header.php';
require_once '../include/db_connect.php';

// Check if the admin is logged in (very important!)
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "Admin") {
    header("Location: index.php"); // Redirect non-admins to customer homepage
    exit;
}

// Function to fetch books from the database
function getBooks($conn, $search_term="", $sort_column = 'id', $sort_direction = 'ASC', $start_index = 0, $items_per_page = 10) {
    $books = [];
    $sql = "SELECT p.id, p.title, p.author, p.price, GROUP_CONCAT(c.name) AS category_names, p.image
        FROM products p
        LEFT JOIN product_categories pc ON p.id = pc.product_id
        LEFT JOIN categories c ON pc.category_id = c.id
        WHERE (p.title LIKE '%$search_term%' OR p.author LIKE '%$search_term%')";
    $sql .= " GROUP BY p.id";
    $sql .= " ORDER BY $sort_column $sort_direction";
    $sql .= " LIMIT $start_index, $items_per_page";
    $result = $conn->query($sql);
     if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $books[] = $row;
        }

    }
     return $books;

}

// Function to count the total number of books
function getTotalBooksCount($conn, $search_term="") {
    $sql = "SELECT COUNT(id) AS total FROM products WHERE (title LIKE '%$search_term%' OR author LIKE '%$search_term%')";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];

}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["add_book"])) {
        $title = $_POST["title"];
        $author = $_POST["author"];
        $price = $_POST["price"];
        $category_ids = isset($_POST['category_ids']) ? $_POST['category_ids'] : []; // Category IDs are handled as an array
        $image = $_POST["image"]; // Add data from photo
        // Add SQL injection checking

        // Insert the new product
        $sql_insert = "INSERT INTO products (title, author, price, image) VALUES (?, ?, ?, ?)";
        $stmt_insert = $conn->prepare($sql_insert);
        $stmt_insert->bind_param("ssdss", $title, $author, $price, $image);

        if ($stmt_insert->execute()) {
            $product_id = $conn->insert_id;  // Get the new product ID

            // Add the product categories
             foreach ($category_ids as $category_id) {
                $sql_insert_category = "INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)";
                 $stmt_insert_category = $conn->prepare($sql_insert_category);
                $stmt_insert_category->bind_param("ii", $product_id, $category_id);
                 $stmt_insert_category->execute();
                $stmt_insert_category->close();
             }
            echo "<script>alert('Book added successfully!');</script>";
        } else {
            echo "Error adding book: " . $conn->error;
        }

    } else if (isset($_POST["edit_book"])) {
         $id = $_POST["id"]; // Get the new product ID

        $title = $_POST["title"];
        $author = $_POST["author"];
        $price = $_POST["price"];

        // Update the existing product in the table
        $sql_update = "UPDATE products SET title=?, author=?, price=? WHERE id = ?";

        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssdii", $title, $author, $price, $id);

           if($stmt_update->execute()){
             echo "<script>alert('Book successfully edited!');</script>";
           }
        else {
            echo "Error editing book: " . $conn->error;
        }
    }
    else if (isset($_POST["delete_book"])) {
        $id = $_POST["id"];

        // Get cart items for the cart
        $sql_delete = "DELETE FROM products WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
         if( $stmt_delete) {
            $stmt_delete->bind_param("i", $id);
             $stmt_delete->execute();
             $stmt_delete->close();
             echo "<script>alert('Delete book success');</script>";

            }
            else {
            echo "Error deleting book: " . $conn->error;
           }
         //After code I was remove from the data.
    }
}

// Set parameters, SQL and other code
$search_term = isset($_GET['search']) ? $_GET['search'] : '';
$sort_column = isset($_GET['sort']) ? $_GET['sort'] : 'id';
$sort_direction = isset($_GET['direction']) ? $_GET['direction'] : 'ASC';
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$items_per_page = 10;
$start_index = ($page - 1) * $items_per_page;

$books = getBooks($conn, $search_term, $sort_column, $sort_direction, $start_index, $items_per_page);
$total_books = getTotalBooksCount($conn, $search_term);

$totalPages = ceil($total_books / $items_per_page);
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
            margin: 5px 0;
        }
        input {
            padding: 3px 7px;
            border: 2px solid #088178;
            border-radius: 5px;
        }

        .edit {
           background-color: #008CBA;
             border: none;
            color: white;
            padding: 10px 15px;
            text-align: center;
           text-decoration: none;
            display: inline-block;
            font-size: 14px;
             margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }

        .delete {
            background-color: #f44336;
              border: none;
            color: white;
            padding: 10px 15px;
           text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
            margin: 4px 2px;
            cursor: pointer;
            border-radius: 5px;
        }
        .pagination a {
         color: black;
         padding: 8px 16px;
         text-decoration: none;
        transition: background-color .3s;
         border: 1px solid #ddd;
      }

       .pagination a.active {
           background-color: #088178;
           color: white;
           border: 1px solid #088178;
           display: inline-block;
        font-size: 14px;
       }

       .pagination a:hover:not(.active) {background-color: #ddd;}
    </style>
</head>
<body>

    <div class="account-container">
        <div class="order-details">
        <h2>Book Management</h2>

  <form method="GET">
            Search Book : <input type="text" name="search" value="<?php echo htmlspecialchars($search_term); ?>">
            <button type="submit">Apply</button>
</form>
            <a href="addbook.php"><button type="submit">Add New Book</button></a>

        <table id="books-table" width="100%">
            <thead>
                <tr>
                   <th><a href="?sort=id&direction=<?php echo ($sort_column == 'id' && $sort_direction == 'ASC') ? 'DESC' : 'ASC'; ?>">Book ID</a></th>
                     <th><a href="?sort=title&direction=<?php echo ($sort_column == 'title' && $sort_direction == 'ASC') ? 'DESC' : 'ASC'; ?>">Title</a></th>
                    <th><a href="?sort=author&direction=<?php echo ($sort_column == 'author' && $sort_direction == 'ASC') ? 'DESC' : 'ASC'; ?>">Author</a></th>
                    <th><a href="?sort=price&direction=<?php echo ($sort_column == 'price' && $sort_direction == 'ASC') ? 'DESC' : 'ASC'; ?>">Price</a></th>
                    <th>Category</th>
                   <th>Actions</th>
                </tr>
            </thead>
                <tbody>
    <?php if (empty($books)): ?>
        <tr>
            <td colspan="7">No books were found.</td>
        </tr>
    <?php else: ?>
        <?php foreach ($books as $book): ?>
            <tr>
                <td><?php echo htmlspecialchars($book["id"]); ?></td>
                <td><?php echo htmlspecialchars($book["title"]); ?></td>
                <td><?php echo htmlspecialchars($book["author"]); ?></td>
                <td>LKR <?php echo htmlspecialchars(number_format($book["price"], 2)); ?></td>
                <td><?php echo htmlspecialchars($book["category_names"]); ?></td>
                 <td class = "actions">

                     <!-- For editing -->
                       <form method="post">
                           <input type="hidden" name="id" value="<?php echo htmlspecialchars($book["id"]); ?>">
                           <button type="submit" name="edit_book" style="border: none; background: none; cursor: pointer;">
                                <a href="editbook.php?id=<?php echo htmlspecialchars($book["id"]); ?>" class = "edit">Edit</a>
                           </button>
                                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($book["id"]); ?>">
                                    <button type="submit" name="delete_book" style="border: none; background: none; cursor: pointer;" onclick="return confirm('Are you sure you want to delete this book?');"> <a  class = "delete">Delete</a></button>
                                </form>

                </td>
            </tr>
        <?php endforeach; ?>
    <?php endif; ?>

</tbody>
</table>

<div class="pagination">
        <?php
            for ($i = 1; $i <= $totalPages; $i++) {
                echo '<a href="?page=' . $i . '&search=' . htmlspecialchars($search_term) . '" class="' . ($page == $i ? 'active' : '') . '">' . $i . '</a>';
            }
        ?>
    </div>
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