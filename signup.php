<?php
include "config.php";

$username_error = "";
$email_error = "";
$password_error = "";
$confirm_password_error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = mysqli_real_escape_string($con, $_POST['username']);
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $password = $_POST['password'];  // Non usare mysqli_real_escape_string per password
    $confirm_password = $_POST['confirm_password'];

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
                // Prepared statement per prevenire SQL injection
                $stmt = $con->prepare("SELECT * FROM signup WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows == 1) {
                    $email_error = "Email already exists";
                }
                $stmt->close();
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

    // Verifica della sicurezza della password (opzionale, ma raccomandata)
    if (!empty($password)) {
        if (strlen($password) < 8) {
            $password_error = "Password must be at least 8 characters long";
        }
    } else {
        $password_error = "Password cannot be blank";
    }

    if (empty($username_error) &&
        empty($email_error) &&
        empty($password_error) &&
        empty($confirm_password_error)) {

        // Hashing della password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Prepared statement per prevenire SQL injection
        $stmt = $con->prepare("INSERT INTO signup (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);
        
        if ($stmt->execute()) {
            header('Location: login.php');
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link rel="stylesheet" href="signup.css">
</head>

<body>

    <div class="box">
        <h2>Sign Up</h2>
        <form action="#" method="post">

            <div class="input_box">
                <input type="text" placeholder="Username" name="username" required value="<?php echo htmlspecialchars($username); ?>">
            </div>
            <?php if(!empty($username_error)) { ?>
                <p class="error"><?php echo $username_error; ?></p>
            <?php } ?>

            <div class="input_box">
                <input type="text" placeholder="Email Id" name="email" required value="<?php echo htmlspecialchars($email); ?>">
            </div>
            <?php if(!empty($email_error)) { ?>
                <p class="error"><?php echo $email_error; ?></p>
            <?php } ?>

            <div class="input_box">
                <input type="password" placeholder="Create Password" name="password" required>
            </div>
            <?php if(!empty($password_error)) { ?>
                <p class="error"><?php echo $password_error; ?></p>
            <?php } ?>

            <div class="input_box">
                <input type="password" placeholder="Confirm Password" name="confirm_password" required>
            </div>
            <?php if(!empty($confirm_password_error)) { ?>
                <p class="error"><?php echo $confirm_password_error; ?></p>
            <?php } ?>

            <div class="links">By creating an account you agree to <a href="#">Terms & Conditions</a></div>

            <button type="submit">Create Account</button>

            <div class="links">Already have an account? <a href="login.php">Login</a></div>
            <div class="links">Need help? <a href="#">Contact Us</a></div>

        </form>
    </div>

</body>

</html>
