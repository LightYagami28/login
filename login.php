<?php

// Include config file
require('config.php');

// Connect to the database
$con = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die('Connection failed: ' . $con->connect_error);
}

// Initialize error variables
$username_error = $email_error = $password_error = $confirm_password_error = "";

// Validate username
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    if (empty($username)) {
        $username_error = "Username cannot be blank";
    } else {
        $username = mysqli_real_escape_string($con, $username);
    }

    // Validate email
    $email = trim($_POST['email']);
    if (empty($email)) {
        $email_error = "Email cannot be blank";
    } elseif (strlen($email) > 50) {
        $email_error = "Email must be less than or equal to 50 characters";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $email_error = "Please enter a valid email";
    } else {
        $email = mysqli_real_escape_string($con, $email);
        $email_check = "SELECT * FROM signup WHERE email = '$email'";
        $query = mysqli_query($con, $email_check);
        if (mysqli_num_rows($query) == 1) {
            $email_error = "Email already exists";
        }
    }

    // Validate password
    $password = trim($_POST['password']);
    if (empty($password)) {
        $password_error = "Password cannot be blank";
    } else {
        $password = mysqli_real_escape_string($con, $password);
    }

    // Validate confirm password
    $confirm_password = trim($_POST['confirm_password']);
    if (empty($confirm_password)) {
        $confirm_password_error = "Confirm password cannot be blank";
    } elseif ($password !== $confirm_password) {
        $confirm_password_error = "Passwords do not match";
    } else {
        $confirm_password = mysqli_real_escape_string($con, $confirm_password);
    }

    // Insert data if no error occurs
    if (empty($username_error) && empty($email_error) && empty($password_error) && empty($confirm_password_error)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);  // Hash the password
        $insert = "INSERT INTO signup (username, email, password) VALUES ('$username', '$email', '$hashed_password')";
        $insert_query = mysqli_query($con, $insert);

        if ($insert_query) {
            header('Location: login.php');
            exit();
        } else {
            $error = "There was an issue with the registration. Please try again.";
        }
    }

    // Close the connection
    $con->close();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
</head>

<body>
    <div class="box">
        <h2>Login</h2>
        <form action="login.php" method="post">
            <?php if (!empty($error)) { ?>
                <p class="error" style="text-align: center;"><?php echo $error; ?></p>
            <?php } ?>
            <div class="input_box">
                <input type="text" name="email" placeholder="Email Id" required value="<?php echo isset($email) ? $email : ''; ?>">
            </div>
            <?php if (!empty($email_error)) { ?>
                <p class="error"><?php echo $email_error; ?></p>
            <?php } ?>
            <div class="input_box">
                <input type="password" name="password" placeholder="Password" required>
            </div>
            <?php if (!empty($password_error)) { ?>
                <p class="error"><?php echo $password_error; ?></p>
            <?php } ?>
            <div class="links" style="text-align: right;"><a href="#">Forgot Password?</a></div>
            <button type="submit">Login</button>
            <div class="links">Don't have an account? <a href="signup.php">Sign Up</a></div>
            <div class="links">Need help? <a href="#">Contact Us</a></div>
        </form>
    </div>
</body>

</html>
