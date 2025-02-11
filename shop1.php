<?php

include 'include/header.php';
require_once __DIR__ . '/include/db_connect.php'; // Database connection
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBB - Catalog</title>
    <link rel="icon" type="image/x-icon" href="images/slogo.ico">

    <!-- font-awesome cdn link -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />

    <!-- custom css file link -->
    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

    <section id="product1" class="section-p1">
        <div class="search-bar-container">
            <div class="search-bar">
              <input type="text" id="searchInput" placeholder="Search books by name or author..." autocomplete="off" />
              <button id="clearButton">X</button>
              <button id="searchButton">search</button>
            </div>
            <ul id="searchResults" class="dropdown-menu"></ul>
          </div>
          <script src="assets/data.js" defer></script>
          <?php
        // Function to fetch and display products based on category ID
        function displayProducts($conn, $category_id, $title, $target_id) {
            // Prepare the SQL statement
            $sql = "SELECT p.id, p.title, p.author, p.price, p.image
                    FROM products p
                    INNER JOIN product_categories c ON p.id = c.product_id
                    WHERE c.category_id = ?";

            // Use a prepared statement to prevent SQL injection
            $stmt = $conn->prepare($sql);
            if ($stmt === false) {
                echo "Prepare failed: " . $conn->error;
                return;
            }

            $stmt->bind_param("i", $category_id); // "i" indicates an integer
            if ($stmt->execute() === false) {
                echo "Execute failed: " . $stmt->error;
                return;
            }

            $result = $stmt->get_result();

            echo "<h3>" . htmlspecialchars($title) . "</h3>";  // Escape title for safety
            echo '<div class="pro-container" id="' . $target_id . '">';
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $product_id = htmlspecialchars($row["id"]); // Escape these, too!
                    $product_title = htmlspecialchars($row["title"]);
                    $product_author = htmlspecialchars($row["author"]);
                    $product_price = htmlspecialchars($row["price"]);
                    $product_image = htmlspecialchars($row["image"]);

                    $product_url = "product.php?id=" . $product_id;

                    echo '<div class="pro" onclick="window.location.href=\'' . $product_url . '\';">';
                    echo '<img src="' . htmlspecialchars($product_image) . '" alt="' . $product_title . '">';
                    echo '<div class="des">';
                    echo '<span>' . $product_author . '</span>';
                    echo '<h5>' . $product_title . '</h5>';
                    echo '<div class="star">';
                    echo '<i class="fas fa-star"></i>';
                    echo '<i class="fas fa-star"></i>';
                    echo '<i class="fas fa-star"></i>';
                    echo '<i class="fas fa-star"></i>';
                    echo '<i class="fas fa-star"></i>';
                    echo '</div>';
                    echo '<h4>LKR ' . $product_price . '</h4>';
                    echo '</div>';
                    echo '<a href="#"><i class="fas fa-shopping-cart cart"></i></a>';
                    echo '</div>';
                }
            } else {
                echo "No products found for " . htmlspecialchars($title);
            }
            echo '</div>'; // Close the pro-container
            $stmt->close(); // Close the prepared statement
        }

        // Call the function for each category
        displayProducts($conn, 4, "Success Secrets", 4);
        displayProducts($conn, 5, "Twisted Series", 5);
        displayProducts($conn, 6, "Dark Verse Series", 6);
        displayProducts($conn, 7, "Ruthless Series", 7);
        displayProducts($conn, 8, "Lauren Asher Book Series", 8);
        ?>

    </section>

    <section id="pagination" class="section-p1">
        <a id="prePage"><i class="fas fa-long-arrow-alt-left"></i></a>
        <a href="shop.php">1</a>
        <a href="shop1.php">2</a>
        <a href="shop2.php">3</a>
        <a id="nextPage"><i class="fas fa-long-arrow-alt-right"></i></a>
    </section>

    <?php
include 'include/footer.php';
?>

    <button id="scrollToTopBtn" class="fas fa-long-arrow-alt-up" onclick="scrollToTop()"></button>

    <!-- javascript script file link -->
    <script src="assets/script.js"></script>
    <script>
        window.onload = function() {
            initializeCartCount(); }
            </script>
</body>
</html>
<?php
if (isset($conn)) { // Check if the connection was established
    $conn->close();   // Close the connection at the end
}
?>