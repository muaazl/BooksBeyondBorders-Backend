<?php
require_once 'header.php';
require_once '../include/db_connect.php';

// Check if the admin is logged in (very important!)
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "Admin") {
    header("Location: index.php"); // Redirect non-admins to customer homepage
    exit;
}

// Function to fetch all categories from the database
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

// Get all categories
$categories = getCategories($conn);

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect data from the form
    $title = $_POST["title"];
    $author = $_POST["author"];
    $price = $_POST["price"];
    $description = $_POST["description"];
    $category_ids = isset($_POST['category_ids']) ? $_POST['category_ids'] : [];
    $image = $_POST["image"]; // This to store to PHP.

    // Prepare the SQL statement to insert new book
    $sql_insert = "INSERT INTO products (title, author, price, description, image) VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("ssdss", $title, $author, $price, $description, $image); // Changed parameters for SQL injections
   if ( $stmt_insert->execute() ) { //Check the statement valid before continue
        $product_id = $conn->insert_id;  // Get the new product ID

        // Add the product categories to the 'product_categories' table
        foreach ($category_ids as $category_id) {
            $sql_insert_category = "INSERT INTO product_categories (product_id, category_id) VALUES (?, ?)";
            $stmt_insert_category = $conn->prepare($sql_insert_category);
            $stmt_insert_category->bind_param("ii", $product_id, $category_id);
            $stmt_insert_category->execute();
            $stmt_insert_category->close();
        }
     echo "<script>alert('Book added successfully!'); window.location.href='books.php';</script>";
        exit;
    } else {
        echo "Error adding book: " . $conn->error;
    }
   //Always close and all
    $stmt_insert->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBB - Add New Book</title>
    <link rel="icon" type="image/x-icon" href="../images/slogo.ico">

    <!-- font-awesome cdn link -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>

    <!-- custom css file link -->
    <link rel="stylesheet" href="../assets/style.css">
    <style>
      /* Basic styling for the form */
        .add-book-form {
            width: 100%;
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

        button {
            background-color: #088178;
           color: white;
           padding: 14px 20px;
           margin: 8px 0;
           border: none;
            border-radius: 4px;
            cursor: pointer;
            display: block;
            width: 100%;
        }

        button:hover {
           background-color:#08776e;
        }

        /* Style for the radio buttons */
input[type="radio"] {
    display: none; /* Hide default radio button */
}

/* Style for the label */
input[type="radio"] + label {
    display: inline-block;
    padding: 8px 14px;
    font-size: 16px;
    border: 2px solid #088178;
    border-radius: 5px;
    background-color: #e1e1e1;
    color: #088178;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-right: 10px;
}

/* Hover effect */
input[type="radio"] + label:hover {
    background-color: #d0d0d0;
}

/* Checked state */
input[type="radio"]:checked + label {
    background-color: #088178;
    color: #ffffff;
}


    </style>
</head>
<body>
<div class="account-container">
    <div class="add-book-form">
       <h2>Add New Book</h2>
        <form method="POST">
            <label for="title">Title:</label>
            <input type="text" name="title" required>

            <label for="author">Author:</label>
            <input type="text" name="author" required>

            <label for="price">Price:</label>
            <input type="number" name="price" step="0.01" required>

              <label for="description">Description:</label>
            <textarea name="description" rows="10" cols="120"></textarea>

            <br><br><label for="image">Image URL:</label>
          <input type="text" name="image" required>
          <br><br><label for="category_ids[]">Categories:</label><br>
          <?php foreach ($categories as $category): ?>
               <input type="radio" id="category<?php echo $category['id']; ?>" name="category_ids[]" value="<?php echo $category['id']; ?>">
              <label for="category<?php echo $category['id']; ?>"><?php echo htmlspecialchars($category['name']); ?></label><br>
            <?php endforeach; ?><br><br>

            <button type="submit" name="add_book">Add Book</button>
        </form>
    </div>
          </div>

<?php require 'footer.php'; ?>

</body>
</html>
<?php
if (isset($conn)) { // Check if the connection was established
    $conn->close();   // Close the connection at the end
}
?>