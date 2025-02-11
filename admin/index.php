<?php 
include 'header.php';
require '../include/db_connect.php';
if (!isset($_SESSION["loggedin"]) || $_SESSION["role"] !== "Admin") {
    header("Location: index.php"); // Redirect non-admins to customer homepage
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBB - Home</title>
    <link rel="icon" type="image/x-icon" href="../images/slogo.ico">

    <!-- font-awesome cdn link -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>

    <!-- custom css file link -->
    <link rel="stylesheet" href="../assets/style.css">

</head>
<body>

<section id="product1" class="section-p1">
<br><br><h2>Admin Panel</h2>
    <section id="categories">
        <a href="orders.php"><div class="cat-box">
            <img src="../images/categories/b12.jpg" alt="">
            <h6>Orders</h6>
        </div></a>
        <a href="users.php"><div class="cat-box">
            <img src="../images/categories/b14.jpg" alt="">
            <h6>Users</h6>
        </div></a>
        <a href="books.php"><div class="cat-box">
            <img src="../images/categories/b13.jpg" alt="">
            <h6>Books</h6>
        </div></a>
    </section>
    </section>

<?php include 'footer.php'; ?>

    <!-- javascript script file link -->
    <script src="assets/script.js"></script>
</body>
</html>