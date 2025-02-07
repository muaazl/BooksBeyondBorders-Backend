<?php
require_once __DIR__ . '/include/db_connect.php';

// Initialize variables to store form data and error messages
$signup_username = $signup_email = "";
$signup_password = $confirm_password = "";
$username_err = $email_err = $password_err = $confirm_password_err = "";

// Process signup form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate Username
    if (empty(trim($_POST["signup_username"]))) {
        $username_err = "Please enter a username.";
    } else {
        $signup_username = trim($_POST["signup_username"]);
    }

    // Validate Email
    if (empty(trim($_POST["signup_email"]))) {
        $email_err = "Please enter an email.";
    } elseif (!filter_var(trim($_POST["signup_email"]), FILTER_VALIDATE_EMAIL)) {
        $email_err = "Please enter a valid email address.";
    } else {
        // Check if email already exists in the database
        $sql = "SELECT id FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $email = trim($_POST["signup_email"]);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $email_err = "This email is already registered.";
        } else {
            $signup_email = $email;
        }
        $stmt->close();
    }

    // Validate Password
    if (empty(trim($_POST["signup_password"]))) {
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST["signup_password"])) < 6) {
        $password_err = "Password must have at least 6 characters.";
    } else {
        $signup_password = trim($_POST["signup_password"]);
    }

    // Validate Confirm Password
    if (empty(trim($_POST["confirm_password"]))) {
        $confirm_password_err = "Please confirm password.";
    } else {
        $confirm_password = trim($_POST["confirm_password"]);
        if ($signup_password != $confirm_password) {
            $confirm_password_err = "Password did not match.";
        }
    }

    // If there are no errors, attempt to insert data into the database
    if (empty($username_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err)) {
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Hash the password
            $hashed_password = password_hash($signup_password, PASSWORD_DEFAULT);

            // Bind parameters
            $stmt->bind_param("sss", $signup_username, $signup_email, $hashed_password);

            // Execute the statement
            if ($stmt->execute()) {
                // Redirect to a success page or login
                header("location: account.php");  // Redirect
                exit; // Ensure that no further code is executed after the redirect
            } else {
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
        } else {
            echo "Error preparing statement: " . $conn->error;
        }
    }

    // Close connection
    // $conn->close(); //Let main page close the connection
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBB - Sign Up</title>
    <link rel="icon" type="image/x-icon" href="images/slogo.ico">
    <link rel="stylesheet" href="assets/style.css">
    <!-- font-awesome cdn link -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />

</head>

<body>

<?php
include 'include/header.php';
?>

    <section id="page-header" class="about-header">
        <h2>#JoinTheStory</h2>
        <p>Sign Up to Begin Your Journey – We’re excited to have you!</p>
    </section>

    <section id="form-details1">
        <div id="form-details1-div">
            <div class="signup-container">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <h2>Sign Up</h2><br><br>
                    
                    <input type="text" id="signup-username" name="signup_username" placeholder="Username" value="<?php echo htmlspecialchars($signup_username); ?>">
                    <span class="help-block"><?php echo $username_err; ?></span>

                    <input type="email" id="signup-email" name="signup_email" placeholder="E-mail" value="<?php echo htmlspecialchars($signup_email); ?>">
                    <span class="help-block"><?php echo $email_err; ?></span>

                    <input type="password" id="signup-password" name="signup_password" placeholder="Password">
                    <span class="help-block"><?php echo $password_err; ?></span>

                    <input type="password" name="confirm_password" placeholder="Confirm Password">
                    <span class="help-block"><?php echo $confirm_password_err; ?></span>

                    <button type="submit" id="signup-btn" class="normal">Sign Up</button>
                    <span>One of us? <a href="login.php">Log In</a></span>
                    <hr class="seperator">
                    <button class="service-btn">
                        <i class="fab fa-google service-icon"></i> Continue with Google</button>
                </form>
            </div>
            <div id="img" class="img-container">
                <img src="images/banner/b18.jpg">
            </div>
        </div>
    </section>

    <?php
include 'include/footer.php';
?>

    <script>
        window.onload = function() {
            initializeCartCount();
        }
    </script>
</body>

</html>
<?php
if (isset($conn)) { // Check if the connection was established
    $conn->close();   // Close the connection at the end
}
?>