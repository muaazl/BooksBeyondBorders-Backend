<?php
require_once __DIR__ . '/include/db_connect.php';

// Initialize variables to store form data and error messages
$login_email = $login_password = "";
$login_email_err = $login_password_err = "";

// Process login form data when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate Email
    if (empty(trim($_POST["login_email"]))) {
        $login_email_err = "Please enter an email.";
    } else {
        $login_email = trim($_POST["login_email"]);
    }

    // Validate Password
    if (empty(trim($_POST["login_password"]))) {
        $login_password_err = "Please enter a password.";
    } else {
        $login_password = trim($_POST["login_password"]);
    }

    // If there are no errors, attempt to authenticate
    if (empty($login_email_err) && empty($login_password_err)) {

        // Prepare a select statement
        $sql = "SELECT id, username, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            // Bind parameters
            $stmt->bind_param("s", $login_email);

            // Execute the statement
            if ($stmt->execute()) {
                // Store result
                $stmt->store_result();

                // Check if email exists, if yes then verify password
                if ($stmt->num_rows == 1) {
                    // Bind result variables
                    $stmt->bind_result($id, $username, $hashed_password);
                    if ($stmt->fetch()) {
                        if (password_verify($login_password, $hashed_password)) {
                            // Password is correct, so start a new session
                            session_start();

                            // Store data in session variables
                            $_SESSION["loggedin"] = true;
                            $_SESSION["id"] = $id;
                            $_SESSION["username"] = $username;

                            // Redirect to a welcome page
                            header("location: account.php");  // Redirect
                            exit; // Ensure that no further code is executed after the redirect
                        } else {
                            // Display an error message if password is not valid
                            $login_password_err = "The password you entered was not valid.";
                        }
                    }
                } else {
                    // Display an error message if email doesn't exist
                    $login_email_err = "No account found with that email.";
                }
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            $stmt->close();
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
    <title>BBB - Log In</title>
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
        <h2>#WelcomeBack</h2>
        <p>Log in to access your account and continue your reading journey.</p>
    </section>

    <section id="form-details1">
        <div id="form-details1-div">
            <div class="login-container">
                
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <h2>Log In</h2><br><br>

                    <input type="email" id="login-email" name="login_email" placeholder="E-mail" value="<?php echo htmlspecialchars($login_email); ?>">
                    <span class="help-block"><?php echo $login_email_err; ?></span>

                    <input type="password" id="login-password" name="login_password" placeholder="Password">
                    <span class="help-block"><?php echo $login_password_err; ?></span>

                    <button type="submit" id="login-btn" class="normal">Log In</button>
                    <span>New here? <a href="signup.php">Sign Up</a></span>
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