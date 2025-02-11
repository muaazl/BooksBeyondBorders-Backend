<?php

require_once 'include/header.php';

// Include PHPMailer files manually
require_once 'phpmailer/src/Exception.php';
require_once 'phpmailer/src/PHPMailer.php';
require_once 'phpmailer/src/SMTP.php';

// Other includes
require_once 'include/db_connect.php';
require_once 'dompdf/autoload.inc.php'; // If you're using Dompdf

use Dompdf\Dompdf;
use Dompdf\Options;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Check if the user is logged in
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}

// Get user ID from session
$user_id = $_SESSION["id"];

// Function to get cart items from the database
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

// Get cart items for the user
$cart_items = getCartItems($conn, $user_id);

// Calculate cart subtotal and total
$cart_subtotal = 0;
foreach ($cart_items as $item) {
    $cart_subtotal += $item["price"] * $item["quantity"];
}
$shipping = "Free";
$total_price = $cart_subtotal;

// Function to generate the HTML invoice content
function generateInvoiceHTML($order_id, $user, $order_items, $total_amount) {
    // Start with the HTML document structure
    $html = '<!DOCTYPE html>
             <html lang="en">
             <head>
                 <meta charset="UTF-8">
                 <meta name="viewport" content="width=device-width, initial-scale=1.0">
                 <title>Order Invoice - #' . $order_id . '</title>
                 <style>
                    body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                    .invoice-container { max-width: 800px; margin: 20px auto; padding: 30px; border: 1px solid #ddd; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
                    .header { text-align: center; padding-bottom: 20px; border-bottom: 2px solid #eee; }
                    .header img { vertical-align: middle; margin-right: 10px; }
                    .header h1 { color: #088178; display: inline; }
                    .customer-info, .order-summary { margin-top: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    .total { margin-top: 20px; text-align: right; font-size: 1.5em; font-weight: bold; background-color: #f2f2f2; padding: 10px; }
                    .footer { text-align: center; margin-top: 30px; padding-top: 20px; border-top: 2px solid #eee; color: #777; }
                    .payment-info { margin-top: 30px; background-color: #f9f9f9; padding: 15px; border: 1px solid #ddd; }
                </style>
                </head>
                <body>
                    <div class="invoice-container">
                        <div class="header">
                        <br><h1>BooksBeyondBorders</h1>
                    </div>

                     <div class="customer-info">
                         <h2>Invoice To</h2>
                         <p><strong>Customer Name:</strong> ' . htmlspecialchars($user["username"]) . '</p>
                         <p><strong>Customer Email:</strong> ' . htmlspecialchars($user["email"]) . '</p>
                     </div>

                     <div class="order-summary">
                         <h2>Order Summary</h2>
                         <p><strong>Invoice Number:</strong> #' . $order_id . '</p>
                         <p><strong>Invoice Date:</strong> ' . date("Y-m-d H:i:s") . '</p>
                         <table>
                             <thead>
                                 <tr>
                                     <th>Item</th>
                                     <th>Quantity</th>
                                     <th>Price</th>
                                     <th>Total</th>
                                 </tr>
                             </thead>
                             <tbody>';

    // Loop through each item in the order to add it to the invoice
    foreach ($order_items as $item) {
        $html .= '<tr>
                     <td>' . htmlspecialchars($item["title"]) . ' - ' . htmlspecialchars($item["author"]) . '</td>
                     <td>' . htmlspecialchars($item["quantity"]) . '</td>
                     <td>LKR ' . htmlspecialchars(number_format($item["price"], 2)) . '</td>
                     <td>LKR ' . htmlspecialchars(number_format($item["price"] * $item["quantity"], 2)) . '</td>
                 </tr>';
    }

    // Complete the table and add the total
    $html .= '</tbody>
                         </table>
                         <div class="total">
                             <strong>Total: LKR ' . htmlspecialchars(number_format($total_amount, 2)) . '</strong>
                         </div>
                     </div>

                     <div class="footer">
                        <p>Thank you for your order!</p>
                        <p>© 2025 Books Beyond Borders</p>
                    </div>
                </div>
             </body>
             </html>';

    return $html;
}

// // Function to send the invoice email
function sendInvoiceEmail($to, $subject, $message, $attachmentPath) {

    
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = 'your_email@example.com';                     //SMTP username
        $mail->Password   = 'your_app_password';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('your_email@example.com', 'Books Beyond Borders');
        $mail->addAddress($to);     //Add a recipient

        //Attachments
        $mail->addAttachment($attachmentPath, 'invoice.pdf');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $message;
        $mail->AltBody = strip_tags($message);

        $mail->send();
        return true;
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        return false;
    }
}
// Form handling for checkout
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["place_order"])) {
        $cart_items = getCartItems($conn, $user_id);
        // Calculate total
        $total_amount = 0;
        foreach ($cart_items as $item) {
            $total_amount += $item["price"] * $item["quantity"];
        }

        // Start transaction
        $conn->begin_transaction();

        try {
            // Create a new order
            $sql_order = "INSERT INTO orders (user_id, order_date, total_amount, status) VALUES (?, NOW(), ?, 'Pending')";
            $stmt_order = $conn->prepare($sql_order);
            $stmt_order->bind_param("id", $user_id, $total_amount);
            $stmt_order->execute();
            $order_id = $conn->insert_id;  // Get the new order ID
            $stmt_order->close();

            // Loop through cart items and add them to the order_items table
            $sql_items = "INSERT INTO order_items (order_id, product_id, quantity, price, title, author, image) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_items = $conn->prepare($sql_items);

            foreach ($cart_items as $item) {
                $stmt_items->bind_param("iiidsss", $order_id, $item["product_id"], $item["quantity"], $item["price"], $item["title"], $item["author"], $item["image"]);
                $stmt_items->execute();
            }
            $stmt_items->close();

            // Fetch user details for the email
            $sql_user = "SELECT username, email FROM users WHERE id = ?";
            $stmt_user = $conn->prepare($sql_user);
            $stmt_user->bind_param("i", $user_id);
            $stmt_user->execute();
            $result_user = $stmt_user->get_result();
            $user = $result_user->fetch_assoc();
            $stmt_user->close();

            // Generate HTML content for the invoice
            $invoice_html = generateInvoiceHTML($order_id, $user, $cart_items, $total_amount);

            // Dompdf configuration
            $options = new Options();
            $options->set('defaultFont', 'Arial');

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($invoice_html);

            // (Optional) Setup the paper size and orientation
            $dompdf->setPaper('A4', 'portrait');

            // Render the HTML as PDF
            $dompdf->render();

            // Output the generated PDF to Browser (inline view)
            $pdf_gen = 'invoice_' . $order_id . '.pdf';
            $output = $dompdf->output();
            file_put_contents($pdf_gen, $output);

            // Clear the cart
            $sql_delete = "DELETE FROM cart_items WHERE cart_id = ?";
            $stmt_delete = $conn->prepare($sql_delete);
             if ($stmt_delete) {
                $sql_cart = "SELECT id FROM carts WHERE user_id = ?";
                $stmt_cart = $conn->prepare($sql_cart);
                $stmt_cart->bind_param("i", $user_id);
                $stmt_cart->execute();
                $result_cart = $stmt_cart->get_result();

                if ($result_cart->num_rows > 0) {
                    $row_cart = $result_cart->fetch_assoc();
                    $cart_id = $row_cart["id"];
                    $stmt_cart->close();
                }

                $stmt_delete->bind_param("i", $cart_id);
                $stmt_delete->execute();
                $stmt_delete->close();
            }

            // Send email

            //Send email
            $email_address = $user["email"];
            $subject = "Your Order Invoice - #" . $order_id;
            $message = "Dear " . htmlspecialchars($user["username"]) . ",\n\nThank you for your order! Please find your invoice attached.\n\nSincerely,\nBooks Beyond Borders";
            $attachmentPath = $pdf_gen;  // Path to the generated PDF

            // Send the email
             $emailSent = sendInvoiceEmail($email_address, $subject, $message, $attachmentPath);

           // Delete the PDF
           unlink($pdf_gen);

           $conn->commit();

             if ($emailSent) {
                 echo "<script>alert('Order placed successfully! Invoice sent to your email.');</script>";
             } else {
                 echo "<script>alert('Order placed successfully, but there was an error sending the email.');</script>";
             }

        }
         catch (Exception $e) {
             //If there was an error with any of the data modifications, rollback
             $conn->rollback();

            //Something went wrong
            header("Location: error.php"); //Go to Error page
            exit();
         }
     header("Location: confirmation.php"); //Go to confirmation page

        }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBB - Checkout</title>
    <link rel="icon" type="image/x-icon" href="images/slogo.ico">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
</head>
<body>

    <section id="page-header" class="about-header">
        <h2>#Checkout</h2>
        <p>Review your order and proceed with checkout</p>
    </section>

    <div class="checkout-container">
    <section id="checkout" class="section-p1">
        <div class="checkout-details">
            <h2>Order Summary</h2>
            <table width="100%">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($cart_items)): ?>
                        <tr>
                            <td colspan="4">Your cart is empty.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($cart_items as $item): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item["title"]); ?></td>
                                <td><?php echo htmlspecialchars($item["quantity"]); ?></td>
                                <td>LKR <?php echo number_format($item["price"], 2); ?></td>
                                <td>LKR <?php echo number_format($item["price"] * $item["quantity"], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="checkout-payment">
            <h2>Payment Details</h2>
            <p>Subtotal: LKR <?php echo number_format($cart_subtotal, 2); ?></p>
            <p>Shipping: <?php echo htmlspecialchars($shipping); ?></p>
            <h4>Total: LKR <?php echo number_format($total_price, 2); ?></h4>

            <form method="post">
                <button type="submit" name="place_order" class="normal">Place Order</button>
            </form>
        </div>
    </section>
    </div>

    <?php include 'include/footer.php'; ?>
    <script src="assets/script.js"></script>
</body>
</html>