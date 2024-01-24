<?php
include "config.php";

$username_error = "";
$email_error = "";
$password_error = "";
$confirm_password_error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = mysqli_real_escape_string($con, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($con, $_POST['confirm_password']);

    if (!empty(trim($username))) {
        if (strlen($username) <= 50) {
            $username = trim($username);
        } else {
            $username_error = "Username must be less than or equal to 50 characters";
        }
    } else {
        $username_error = "Username cannot be blank";
    }

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

    if (!empty(trim($confirm_password))) {
        if ($password === $confirm_password) {
            $confirm_password = trim($confirm_password);
        } else {
            $confirm_password_error = "Password not matched";
        }
    } else {
        $confirm_password_error = "Confirm password cannot be blank";
    }

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
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="signup.css">
</head>

<body>

<div class="box">
        <h2>Sign Up</h2>
        <form action="#" method="post">

            <div class="input_box">
                <input type="text" placeholder="Username" name="username" required>
            </div>
            <?php if(!empty($username_error)){ ?>
                <p class="error"><?php echo $username_error ?></p>
            <?php } ?>

            <div class="input_box">
                <input type="text" placeholder="Email Id" name="email" required>
            </div>
            <?php if(!empty($email_error)){ ?>
                <p class="error"><?php echo $email_error ?></p>
            <?php } ?>

            <div class="input_box">
                <input type="password" placeholder="Create Password" name="password" required>
            </div>
            <?php if(!empty($password_error)){ ?>
                <p class="error"><?php echo $password_error ?></p>
            <?php } ?>

            <div class="input_box">
                <input type="password" placeholder="Confirm Password" name="confirm_password" required>
            </div>
            <?php if(!empty($confirm_password_error)){ ?>
                <p class="error"><?php echo $confirm_password_error ?></p>
            <?php } ?>

            <div class="links">By creating an account you agree to <a href="#">Terms & Conditions</a></div>


            <button type="submit">Create Account</button>

            <div class="links">Already have an account? <a href="login.php">Login</a></div>
            <div class="links">Need help? <a href="#">Contact Us</a></div>

        </form>
    </div>