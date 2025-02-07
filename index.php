<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBB - Home</title>
    <link rel="icon" type="image/x-icon" href="images/slogo.ico">

    <!-- font-awesome cdn link -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>

    <!-- custom css file link -->
    <link rel="stylesheet" href="assets/style.css">

</head>
<body>
    
<?php
include 'include/header.php';
?>

    <section id="hero">
        <h4>special-offer</h4>
        <h2>Limited Time Deals</h2>
        <h1>Save Big on Bestsellers</h1>
        <p><marquee>New Arrivals: Pre-order Now and Save!</marquee></p>
        <a href="shop.php"><button>explore now</button></a>
    </section>

    
    
    <div id= "product2">
    <section id="product1" class="section-p1">
    <h2>Featured Books</h2>
    <p>#1 Bestseller Collection</p>
    <div class="pro-container">
        <?php
            require_once __DIR__ . '/include/db_connect.php';

            $conn = new mysqli($host, $username, $password, $database);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // SQL query to retrieve products (adjust as needed, e.g., limit to featured books)
            $sql = "SELECT p.id, p.title, p.author, p.price, p.image FROM products p INNER JOIN product_categories c ON p.id = c.product_id WHERE c.category_id = 1 LIMIT 6";  // No WHERE clause for ALL products
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // Output data of each row
                while($row = $result->fetch_assoc()) {
                    $product_id = $row["id"];
                    $product_title = $row["title"];
                    $product_author = $row["author"];
                    $product_price = $row["price"];
                    $product_image = $row["image"];

                    // Construct the product URL
                    $product_url = "product.php?id=" . $product_id;

                    echo '<div class="pro" onclick="window.location.href=\'' . $product_url . '\';">';
                    echo '<img src="'.$product_image.'" alt="' . $product_title . '">';
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

            $conn->close();
            ?>
    </div>
    </section>
    </div>

    <section id="banner" class="section-p1">
        <h4>Best-Selling Book of the Month</h4>
        <h2>Discover the <span>#1</span> Bestseller—Available Now!</h2>
        <a href="shop.php"><button class="normal">shop now</button></a>
    </section>

    <section id="product1" class="section-p1">
    <h2>Categories</h2>
    <section id="categories">
        <?php
        // Define the categories and their corresponding IDs and URLs
        $categories = [
            ['name' => 'Featured', 'id' => 1, 'image' => 'images/categories/b1.jpg'],
            ['name' => 'Newbie', 'id' => 2, 'image' => 'images/categories/b2.jpg'],
            ['name' => 'Self-Help', 'id' => 4, 'image' => 'images/categories/b3.jpg', 'page' => 'shop1.php'],
            ['name' => 'Vi Keeland', 'id' => 3, 'image' => 'images/categories/b4.jpg'],
            ['name' => 'Series', 'id' => 5, 'image' => 'images/categories/b5.jpg', 'page' => 'shop1.php'],
            ['name' => 'Novels', 'id' => 9, 'image' => 'images/categories/b6.jpg', 'page' => 'shop2.php'],
        ];

        foreach ($categories as $category) {
            $category_name = htmlspecialchars($category['name']);
            $category_id = $category['id'];
            $category_image = htmlspecialchars($category['image']);
            $page = isset($category['page']) ? $category['page'] : 'shop.php';
            $target_id = strtolower(str_replace(' ', '-', $category_id));
            echo '<a href="' . $page . '#' . $target_id . '"><div class="cat-box">';
            echo '<img src="' . $category_image . '" alt="' . $category_name . '">';
            echo '<h6>' . $category_name . '</h6>';
            echo '</div></a>';
        }
        ?>
    </section>
</section>

    <section id="sm-banner" class="section-p1">
        <div class="banner-box">
            <h4>Membership Promotion</h4>
            <h2>Join our Reading Club</h2>
            <span>for exclusive discounts just for you!</span>
            <a href="signup.php"><button class="white">learn more</button></a>
        </div>
        <div class="banner-box">
            <h4>Sepcial Promotion</h4>
            <h2>Buy the New Vi Keeland Book</h2>
            <span>and Get 10% Off on your total bill - t & c apply</span>
            <a href="shop.php#vi-keeland"><button class="white">learn more</button></a>
        </div>
    </section>

    <section id="banner3">
        <div class="banner-box" onclick="window.location.href='signup.php';">
            <h2>SIGN UP TODAY</h2>
            <h3>Enter to Win a Gift Card!</h3>
        </div>
        <div class="banner-box" onclick="window.location.href='signup.php';">
            <h2>FREE SHIPPING</h2>
            <h3>On Orders Over LKR 7999!</h3>
        </div>
        <div class="banner-box" onclick="window.location.href='signup.php';">
            <h2>REFER A FRIEND</h2>
            <h3>Both Get 15% Off!</h3>
        </div>
    </section>
    
    

    <?php
include 'include/footer.php';
?>

    <!-- javascript script file link -->
    <script src="assets/script.js"></script>
    <script src="assets/products.js"></script>
    <script>
window.onload = function() {
    initializeCartCount(); }
    </script>
</body>
</html>