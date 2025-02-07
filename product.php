<?php
require_once 'include/header.php';
require_once __DIR__ . '/include/db_connect.php';

// Get the product ID from the URL
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Sanitize input

// Function to add a product to the cart
function addToCart($conn, $user_id, $product_id)
{
    // Get product details
    $sql_product = "SELECT id, title, author, price, image FROM products WHERE id = ?";
    $stmt_product = $conn->prepare($sql_product);
    $stmt_product->bind_param("i", $product_id);
    $stmt_product->execute();
    $result_product = $stmt_product->get_result();

    if ($result_product->num_rows > 0) {
        $product = $result_product->fetch_assoc();
        $price = $product['price'];
        $title = $product['title'];
        $author = $product['author'];
        $image = $product['image'];
        $stmt_product->close();

        // Get the cart ID for the user
        $sql_cart = "SELECT id FROM carts WHERE user_id = ?";
        $stmt_cart = $conn->prepare($sql_cart);
        $stmt_cart->bind_param("i", $user_id);
        $stmt_cart->execute();
        $result_cart = $stmt_cart->get_result();

        if ($result_cart->num_rows > 0) {
            $row_cart = $result_cart->fetch_assoc();
            $cart_id = $row_cart["id"];
            $stmt_cart->close();

            // Check if the product is already in the cart
            $sql_check = "SELECT id, quantity FROM cart_items WHERE cart_id = ? AND product_id = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("ii", $cart_id, $product_id);
            $stmt_check->execute();
            $result_check = $stmt_check->get_result();

            if ($result_check->num_rows > 0) {
                $row_check = $result_check->fetch_assoc();
                $existing_quantity = $row_check["quantity"];
                $cart_item_id = $row_check["id"];
                $stmt_check->close();

                // Update the quantity if the product is already in the cart
                $new_quantity = $existing_quantity + 1;
                $sql_update = "UPDATE cart_items SET quantity = ? WHERE id = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("ii", $new_quantity, $cart_item_id);
                $stmt_update->execute();
                $stmt_update->close();
            } else {
                $stmt_check->close();
                // Add the product to the cart_items table
                $sql_insert = "INSERT INTO cart_items (cart_id, product_id, quantity, price, title, author, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
                $stmt_insert = $conn->prepare($sql_insert);
                //The quantity that used to be a string is now an int
                $quantity=1;
                $stmt_insert->bind_param("iiidsss", $cart_id, $product_id, $quantity, $price, $title, $author, $image);
                $stmt_insert->execute();
                $stmt_insert->close();
            }
        } else {
            $stmt_cart->close();
            // Create a new cart for the user
            $sql_create_cart = "INSERT INTO carts (user_id) VALUES (?)";
            $stmt_create_cart = $conn->prepare($sql_create_cart);
            $stmt_create_cart->bind_param("i", $user_id);
            $stmt_create_cart->execute();
            $stmt_create_cart->close();

            // Get the new cart ID
            $cart_id = $conn->insert_id;

            // Add the product to the cart_items table
            $sql_insert = "INSERT INTO cart_items (cart_id, product_id, quantity, price, title, author, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_insert = $conn->prepare($sql_insert);
            //The quantity that used to be a string is now an int
            $quantity=1;
            $stmt_insert->bind_param("iiidsss", $cart_id, $product_id, $quantity, $price, $title, $author, $image);
            $stmt_insert->execute();
            $stmt_insert->close();
        }

        echo "<script>
            alert('Item added to cart successfully!');
            window.history.back();
        </script>";
    } else {
        echo "<script>alert('Product not found.');</script>";
    }
}

// Check if the user is logged in
$user_id = isset($_SESSION["id"]) ? $_SESSION["id"] : 0;

// Handle adding to cart
if (isset($_POST["add_to_cart"])) {
    if ($user_id > 0) {
        addToCart($conn, $user_id, $product_id);
    } else {
        echo "<script>
                alert('Please log in to add items to your cart.');
                window.location.href = 'login.php';
              </script>";
        exit();
    }
}

if ($product_id > 0) {
    // SQL query to retrieve the product details
    $sql = "SELECT id, title, author, price, description, image FROM products WHERE id = ?";  // Prepared statement
    $stmt = $conn->prepare($sql);  // Prepare the statement
    $stmt->bind_param("i", $product_id); // Bind the parameter (i = integer)
    $stmt->execute(); // Execute the query
    $result = $stmt->get_result(); // Get the result

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $product_title = htmlspecialchars($row["title"]); // Sanitize output
        $product_author = htmlspecialchars($row["author"]); // Sanitize output
        $product_price = htmlspecialchars($row["price"]); // Sanitize output
        $product_description = htmlspecialchars($row["description"]); // Sanitize output
        $product_image = htmlspecialchars($row["image"]); // Sanitize output
    }

    $stmt->close(); // Close the statement
} else {
    echo "<script>document.getElementById('productdetails').innerHTML = '<p>Invalid product ID.</p>';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBB - Product</title>
    <link rel="icon" type="image/x-icon" href="images/slogo.ico">

    <!-- font-awesome cdn link -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />

    <!-- custom css file link -->
    <link rel="stylesheet" href="assets/style.css">

</head>

<body>

    <section id="productdetails" class="section-p1">
        <button class="back-icon" onclick="goBack()"><i class="fas fa-arrow-left"></i> Back</button>
        <div class="single-pro-image">
            <center><img src="" id="MainImg" alt="Product Image" class="thumbnail" onclick="showOverlay(this)"></center>
            <div id="overlay" class="overlay" onclick="closeOverlay()">
                <img id="overlay-img" src="" alt="Expanded view">
            </div>
        </div>
        <div class="single-pro-details">
            <h2 id="product-name"></h2>
            <h3 id="product-author"></h3><br>
            <h1 id="product-price"></h1>
            <h4>Book Description</h4>
            <span id="product-description"></span><br>
            <form method="post">
                <button class="normal" name="add_to_cart">Add to Cart</button>
            </form>
        </div>
    </section>

    <script>
        function goBack() {
            window.history.back();
        }

        function showOverlay(img) {
            document.getElementById('overlay-img').src = img.src;
            document.getElementById('overlay').style.display = 'flex';
        }

        function closeOverlay() {
            document.getElementById('overlay').style.display = 'none';
        }
    </script>

    <?php
    require_once __DIR__ . '/include/db_connect.php';

    // Get the product ID from the URL
    $product_id = isset($_GET['id']) ? intval($_GET['id']) : 0; // Sanitize input

    if ($product_id > 0) {
        // SQL query to retrieve the product details
        $sql = "SELECT id, title, author, price, description, image FROM products WHERE id = ?";  // Prepared statement
        $stmt = $conn->prepare($sql);  // Prepare the statement
        $stmt->bind_param("i", $product_id); // Bind the parameter (i = integer)
        $stmt->execute(); // Execute the query
        $result = $stmt->get_result(); // Get the result

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $product_title = htmlspecialchars($row["title"]); // Sanitize output
            $product_author = htmlspecialchars($row["author"]); // Sanitize output
            $product_price = htmlspecialchars($row["price"]); // Sanitize output
            $product_description = htmlspecialchars($row["description"]); // Sanitize output
            $product_image = htmlspecialchars($row["image"]); // Sanitize output

            // Output the product details directly into the HTML
            echo "<script>";
            echo "document.getElementById('MainImg').src = '" . htmlspecialchars($product_image) . "';"; // Adjusted image path
            echo "document.getElementById('product-name').innerText = '" . $product_title . "';";
            echo "document.getElementById('product-author').innerText = '" . $product_author . "';";
            echo "document.getElementById('product-price').innerText = 'LKR " . $product_price . "';";
            echo "document.getElementById('product-description').innerText = '" . $product_description . "';";
            echo "</script>";
        } else {
            echo "<script>document.getElementById('productdetails').innerHTML = '<p>Product not found.</p>';</script>";
        }

        $stmt->close(); // Close the statement
    } else {
        echo "<script>document.getElementById('productdetails').innerHTML = '<p>Invalid product ID.</p>';</script>";
    }
    ?>

    <section id="banner" class="section-m1">
        <h4>Best-Selling Book of the Month</h4>
        <h2>Discover the <span>#1</span> Bestseller—Available Now!</h2>
        <a href="shop.php"><button class="normal">shop now</button></a>
    </section>

    <div id="product2">
        <section id="product1" class="section-p1">
            <h2>Featured Books</h2>
            <p>Our Best Reads</p>
            <div class="pro-container">

                <?php
                require_once __DIR__ . '/include/db_connect.php';

                // SQL query to retrieve products (adjust as needed, e.g., limit to featured books)
                $sql = "SELECT p.id, p.title, p.author, p.price, p.image FROM products p INNER JOIN product_categories c ON p.id = c.product_id WHERE c.category_id = 1 LIMIT 6";  // No WHERE clause for ALL products
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    // Output data of each row
                    while ($row = $result->fetch_assoc()) {
                        $product_id = htmlspecialchars($row["id"]);
                        $product_title = htmlspecialchars($row["title"]);
                        $product_author = htmlspecialchars($row["author"]);
                        $product_price = htmlspecialchars($row["price"]);
                        $product_image = htmlspecialchars($row["image"]);

                        // Construct the product URL
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
                    echo "No products found";
                }
                ?>

            </div>
        </section>
    </div>

    <?php
    include 'include/footer.php';
    ?>

    <!-- javascript script file link -->
    <script src="assets/script.js"></script>
</body>

</html>

<?php
if (isset($conn)) { // Check if the connection was established
    $conn->close();   // Close the connection at the end
}
?>