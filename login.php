<?php

// Include MySQLi library
require_once('mysqli.php');

// Include config file
require('config.php');

// Connect to the database
$con = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($con->connect_error) {
    die('Connection failed: ' . $con->connect_error);
}

// Username validation
if (!empty(trim($username))) {
    $username = mysqli_real_escape_string($con, $_POST['username']);
} else {
    $username_error = "Username cannot be blank";
}

// Email validation
if (!empty(trim($email))) {
    if (strlen($email) <= 50) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $email_check = "SELECT * FROM signup WHERE email = '$email'";
            $query = mysqli_query($con, $email_check);
            if (mysqli_num_rows($query) == 1) {
                $email_error = "Email already exists";
            }
        } else {
            $email_error = "Please enter a valid email";
        }
    } else {
        $email_error = "Email must be less than or equal to 50 characters";
    }
} else {
    $email_error = "Email cannot be blank";
}

// Password validation
// ... (your existing password validation code)

// Confirm password validation
if (!empty(trim($confirm_password))) {
    if ($password === $confirm_password) {
        $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);
    } else {
        $confirm_password_error = "Password not matched";
    }
} else {
    $confirm_password_error = "Confirm password cannot be blank";
}

// Insert data if no error occurs
if (empty($username_error) &&
    empty($email_error) &&
    empty($password_error) &&
    empty($confirm_password_error)) {

    $insert = "INSERT INTO signup (username, email, password) VALUES ('$username', '$email', '$password')";
    $insert_query = mysqli_query($con, $insert);

    if ($insert_query) {
        header('location:login.php');
    }
}

// Close the connection
$con->close();
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
        <form action="#" method="post">
            <?php if (!empty($error)) { ?>
                <p class="error" style="text-align: center;"><?php echo $error; ?></p>
            <?php } ?>
            <div class="input_box">
                <input type="text" name="email" placeholder="Email Id" required>
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
