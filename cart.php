<?php
require_once 'include/header.php';
require_once 'include/db_connect.php';
// Check if the user is logged in
$user_id = isset($_SESSION["id"]) ? $_SESSION["id"] : 0;

// Function to fetch cart items from the database
function getCartItems($conn, $user_id) {
    $cart_items = [];

    // Get the cart ID for the user
    $sql_cart = "SELECT id FROM carts WHERE user_id = ?";
    $stmt_cart = $conn->prepare($sql_cart);
    $stmt_cart->bind_param("i", $user_id);
    $stmt_cart->execute();
    $result_cart = $stmt_cart->get_result();

    if ($result_cart->num_rows > 0) {
        $row_cart = $result_cart->fetch_assoc();
        $cart_id = $row_cart["id"];

        // Get the cart items for the cart
        $sql = "SELECT ci.id, ci.product_id, ci.quantity, ci.price, ci.title, ci.author, ci.image
                FROM cart_items ci
                WHERE ci.cart_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $cart_id);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $cart_items[] = $row;
        }
    }
    $stmt_cart->close();
    return $cart_items;
}

// Handle quantity updates and item removals
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["update_quantity"])) {
        $item_id = $_POST["item_id"];
        $new_quantity = $_POST["quantity"];

        // Validate quantity
        if ($new_quantity > 0) {
            // Update quantity in the database
            $sql = "UPDATE cart_items SET quantity = ? WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ii", $new_quantity, $item_id);
            $stmt->execute();
            $stmt->close();
        } else {
            // Remove item from cart if quantity is 0 or less
            $sql = "DELETE FROM cart_items WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $item_id);
            $stmt->execute();
            $stmt->close();
        }
    } elseif (isset($_POST["remove_item"])) {
        $item_id = $_POST["item_id"];

        // Remove item from cart
        $sql = "DELETE FROM cart_items WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $item_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Get cart items for the user
$cart_items = getCartItems($conn, $user_id);

// Calculate cart subtotal
$cart_subtotal = 0;
foreach ($cart_items as $item) {
    $cart_subtotal += $item["price"] * $item["quantity"];
}

// Set shipping to free
$shipping = "Free";

// Calculate total price
$total_price = $cart_subtotal;

// Display cart items in a table
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBB - Cart</title>
    <link rel="icon" type="image/x-icon" href="images/slogo.ico">

    <link rel="stylesheet" href="assets/style.css">
    <!-- font-awesome cdn link -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />

</head>

<body>


    <section id="page-header" class="about-header">
        <h2>#Cart</h2>
        <p>Add your coupon code & SAVE upto 70%!</p>
    </section>

    <section id="cart" class="section-p1">
        <table id="cart-table" width="100%">
            <thead>
                <tr>
                    <td>Remove</td>
                    <td>Image</td>
                    <td>Product</td>
                    <td>Price</td>
                    <td>Quantity</td>
                    <td>Subtotal</td>
                </tr>
            </thead>
            <tbody id="cart-body">
                <?php if (empty($cart_items)): ?>
                    <tr>
                        <td colspan="6">No items in cart.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($cart_items as $item): ?>
                        <?php
                        $item_subtotal = $item["price"] * $item["quantity"];
                        ?>
                        <tr>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item["id"]); ?>">
                                    <button type="submit" name="remove_item" style="border: none; background: none; cursor: pointer;">
                                        <i class="fas fa-times-circle" style="color:black"></i>
                                    </button>
                                </form>
                            </td>
                            <td><img src="<?php echo htmlspecialchars($item["image"]); ?>" alt="<?php echo htmlspecialchars($item["title"]); ?>" width="50"></td>
                            <td><?php echo htmlspecialchars($item["title"]) . " - " . htmlspecialchars($item["author"]); ?></td>
                            <td>LKR <?php echo number_format($item["price"], 2); ?></td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="item_id" value="<?php echo htmlspecialchars($item["id"]); ?>">
                                    <input type="number" name="quantity" value="<?php echo htmlspecialchars($item["quantity"]); ?>" min="1" onchange="this.form.submit()">
                                    <input type="hidden" name="update_quantity">
                                </form>
                            </td>
                            <td>LKR <?php echo number_format($item_subtotal, 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <section id="cart-add" class="section-p1">
        <div class="coupon">
            <h3>Apply Coupon</h3>
            <div>
                <input type="text" placeholder="Enter Your Coupon">
                <button class="normal">apply</button>
            </div>
        </div>
        <div class="subtotal">
            <h3>Cart Totals</h3>
            <table id="cart-totals">
                <tr>
                    <td>Cart Subtotal</td>
                    <td id="cart-subtotal">LKR <?php echo number_format($cart_subtotal, 2); ?></td>
                </tr>
                <tr>
                    <td>Shipping</td>
                    <td><?php echo htmlspecialchars($shipping); ?></td>
                </tr>
                <tr>
                    <td><strong>Total</strong></td>
                    <td id="total-price">LKR <?php echo number_format($total_price, 2); ?></td>
                </tr>
            </table>
            <a href="checkout.php"><button class="normal">proceed to checkout</button></a>
        </div>
    </section>

    <?php include 'include/footer.php'; ?>

    <!-- javascript -->
    <script src="assets/script.js"></script>
</body>

</html>
<?php
if (isset($conn)) { // Check if the connection was established
    $conn->close();   // Close the connection at the end
}
?>