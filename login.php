<?php
include 'include/header.php';
require_once __DIR__ . '/include/db_connect.php';

// Initialize variables
$login_email = $login_password = "";
$login_email_err = $login_password_err = "";

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

    // If no errors, proceed with authentication
    if (empty($login_email_err) && empty($login_password_err)) {
        $sql = "SELECT id, username, password, role FROM users WHERE email = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $login_email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows == 1) {
                $stmt->bind_result($id, $username, $hashed_password, $role);
                if ($stmt->fetch()) {
                    if (password_verify($login_password, $hashed_password)) {
                        $_SESSION["loggedin"] = true;
                        $_SESSION["id"] = $id;
                        $_SESSION["username"] = $username;
                        $_SESSION["role"] = $role;

                        // Redirect based on role
                        if ($role === "Admin") {
                            header("Location: admin/index.php");
                        } else {
                            header("Location: index.php");
                        }
                        exit;
                    } else {
                        $login_password_err = "Invalid password.";
                    }
                }
            } else {
                $login_email_err = "No account found with that email.";
            }
            $stmt->close();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BBB - Log In</title>
    <link rel="icon" type="image/x-icon" href="images/slogo.ico">
    <link rel="stylesheet" href="assets/style.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" />
</head>
<body>
    <section id="page-header" class="about-header">
        <h2>#WelcomeBack</h2>
        <p>Log in to access your account and continue your reading journey.</p>
    </section>
    <section id="form-details1">
        <div id="form-details1-div">
            <div class="login-container">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <h2>Log In</h2><br><br>
                    <input type="email" name="login_email" placeholder="E-mail" value="<?php echo htmlspecialchars($login_email); ?>">
                    <span class="help-block"><?php echo $login_email_err; ?></span>
                    <input type="password" name="login_password" placeholder="Password">
                    <span class="help-block"><?php echo $login_password_err; ?></span>
                    <button type="submit" class="normal">Log In</button>
                    <span>New here? <a href="signup.php">Sign Up</a></span>
                    <hr class="seperator">
                    <button class="service-btn">
                        <i class="fab fa-google service-icon"></i> Continue with Google
                    </button>
                </form>
            </div>
            <div id="img" class="img-container">
                <img src="images/banner/b18.jpg">
            </div>
        </div>
    </section>
<?php include 'include/footer.php'; ?>
</body>
</html>
<?php if (isset($conn)) { $conn->close(); } ?>
