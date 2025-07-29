<?php
session_start();
$con = mysqli_connect("localhost", "root", "", "kylies");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $result = mysqli_query($con, "SELECT * FROM kyliescustomers WHERE Email = '$email'");

    if (mysqli_num_rows($result) > 0) {
        // Email found, redirect to reset page
        header("Location: pass-reset.php?email=" . urlencode($email));
        exit();
    } else {
        $error = "Email not found. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="forgotpassword.css">
    <title>Forgot Password</title>
</head>
<body>
    <div class="container">
        <img src="images/bg lg.jpg" alt="">    

        <div class="row">
            <h1>Forgot Password ?</h1>
            <h6 class="information-text">Enter your Registered Email to reset password.</h6>

            <?php if (!empty($error)) { echo "<p style='color:red; margin-top:10px;'>$error</p>"; } ?>

            <form method="POST">
                <div class="form-group">
                    <input type="email" name="email" id="Email" required>
                    <p><label for="Email">Email</label></p>
                    <button type="submit">Reset Password</button>
                </div>
            </form>

            <div class="footer">
                <h5>Not a Member? <a href="login.php">Register</a></h5>
                <h5><a href="login.php">Already have an account </a></h5>
            </div>
        </div>
    </div>
</body>
</html>
