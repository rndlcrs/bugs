<?php
$con = mysqli_connect("localhost", "root", "", "kylies");

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

$new_password_err = $confirm_password_err = '';
$new_password = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($con, $_POST['email']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Validation
    if (empty($new_password)) {
        $new_password_err = "Please enter a new password.";
    } elseif (strlen($new_password) < 6) {
        $new_password_err = "Password must be at least 6 characters.";
    }

    if ($new_password !== $confirm_password) {
        $confirm_password_err = "Passwords do not match.";
    }

    // Update if valid
    if (empty($new_password_err) && empty($confirm_password_err)) {
        $hashed_pass = password_hash($new_password, PASSWORD_DEFAULT);
        $query = "UPDATE kyliescustomers SET Password = '$hashed_pass' WHERE Email = '$email'";

        if (mysqli_query($con, $query)) {
            echo "<script>alert('Password successfully updated.'); window.location.href='index.html';</script>";
        } else {
            echo "Error updating password: " . mysqli_error($con);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link rel="stylesheet" href="forgotpassword.css">
</head>
<body>
    <div class="container">
        <img src="images/bg lg.jpg" alt="">

        <div class="row" style="margin-top: 60px;">
            <h1>Reset Password</h1>
            <h6 class="information-text">Please enter and confirm your new password.</h6>

            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <input type="hidden" name="email" value="<?php echo htmlspecialchars($_GET['email'] ?? $_POST['email'] ?? ''); ?>">

                <div class="form-group">
                    <label>New Password</label>
                    <input type="password" name="new_password" required>
                    <?php if (!empty($new_password_err)) echo "<p style='color: red;'>$new_password_err</p>"; ?>
                </div>

                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" required>
                    <?php if (!empty($confirm_password_err)) echo "<p style='color: red;'>$confirm_password_err</p>"; ?>
                </div>

                <div class="form-group">
                    <button type="submit">Submit</button>
                </div>
            </form>

            <div class="footer">
                <h5><a href="index.html">Back to Login</a></h5>
            </div>
        </div>
    </div>
</body>
</html>
