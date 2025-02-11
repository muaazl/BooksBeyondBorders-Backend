<?php
require_once 'header.php';
require_once '../include/db_connect.php';

// Check if the admin is logged in and has the correct role
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "Admin") {
    header("Location: index.php");
    exit;
}

// Check if the book ID is set
$book_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// If the book ID is not valid, redirect to the books page
if ($book_id <= 0) {
    header("Location: books.php");
    exit;
}

// Function to get book details from database
function getBookDetails($conn, $book_id) {
    $sql = "SELECT id, title, author, price, description, image FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $book_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        return $result->fetch_assoc();
    } else {
        return null; // Book not found
    }
}

// Function to get all categories from the database
function getCategories($conn) {
    $categories = [];
    $sql = "SELECT id, name FROM categories";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $categories[] = $row;
        }
    }
    return $categories;
}

// Fetch the book details and categories
$book = getBookDetails($conn, $book_id);
$categories = getCategories($conn);

// If the book is not found, redirect to the books page
if (!$book) {
    header("Location: books.php");
    exit;
}

// Handle form submissions
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect data from the form
    $title = $_POST["title"];
    $author = $_POST["author"];
    $price = $_POST["price"];
    $description = $_POST["description"];
   $image = $_POST["image"];

    // Update the existing product in the table
    $sql_update = "UPDATE products SET title=?, author=?, price=?, description=?, image=? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("ssdssi", $title, $author, $price, $description, $image, $book_id);

    if ($stmt_update->execute()) {
         echo "<script>alert('Book updated successfully!'); window.location.href='books.php';</script>";
               exit;
        } else {
            echo "Error updating book: " . $conn->error;
        }

    $stmt_update->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBB - Edit Book</title>
    <link rel="icon" type="image/x-icon" href="../images/slogo.ico">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
    <link rel="stylesheet" href="../assets/style.css">
       <style>
      /* Basic styling for the form */
        .add-book-form {
            width: 50%;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
             background-color: #f9f9f9;
        }

        label {
            display: block;
            margin-bottom: 5px;

       }

    input[type=text], input[type=number], select {
            width: 100%;
            padding: 12px 20px;
            margin: 8px 0;
             display: inline-block;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
       }

       .button {
        display: grid;
        width: 100%;
       }

       button {
            background-color: #088178;
           color: white;
           padding: 14px 20px;
           margin: 8px 0;
           border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
        }

        button:hover {
           background-color:rgb(5, 82, 76);
        }

    </style>
</head>
<body>

<div class="account-container">
<h2>Edit Book</h2>
    <form method="POST">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($book["id"]); ?>">

        <label for="title">Title:</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($book["title"]); ?>" required>

        <label for="author">Author:</label>
        <input type="text" name="author" value="<?php echo htmlspecialchars($book["author"]); ?>" required>

        <label for="price">Price:</label>
        <input type="number" name="price" step="0.01" value="<?php echo htmlspecialchars($book["price"]); ?>" required>

        <label for="description">Description:</label>
        <textarea name="description" rows="4" cols="50"><?php echo htmlspecialchars($book["description"]); ?></textarea>

       <label for="image">Image URL:</label>
        <input type="text" name="image" value="<?php echo htmlspecialchars($book["image"]); ?>">
        <div class="button">
        <button type="submit" name="edit_book">Update Book</button>
        <a href="books.php"><button>Cancel</button></a>
    </div>
    </form>
</div>

<?php require 'footer.php'; ?>

</body>
</html>
<?php
if (isset($conn)) { // Check if the connection was established
    $conn->close();   // Close the connection at the end
}
?>