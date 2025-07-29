<?php 
    session_start();
    include("php/config.php");

        if (isset($_POST['submit'])) {
            $email = mysqli_real_escape_string($con, $_POST['Email']);
            $password = mysqli_real_escape_string($con, $_POST['Password']);

            $query = "SELECT * FROM kyliesdatabase WHERE Email = '$email' AND Password = '$password'";
            $result = mysqli_query($con, $query);

            if ($row = mysqli_fetch_assoc($result)) {
                $_SESSION['admin_email'] = $row['Email'];
                header("Location: ../ADMIN/dashboard.php");
                exit();
            } else {
                $_SESSION['error'] = "Invalid Email or Password";
                header("Location: adminlogin.php");
                exit();
            }
        }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="adminlogin.css">
    <title>ADMIN LOGIN</title>
</head>

<body>
    
        <div class="loginform">
                <h1>ADMIN LOGIN</h1>
                    <div class="form-content">

                        <form action="" method="POST" class="login">
            <div class="field">
                <input type="text" name="Email" placeholder="Email Address" required>
                <i class='bx bxs-envelope'></i>
            </div>

            <div class="field">
                <input type="password" name="Password" placeholder="Password" required>
                <i class='bx bxs-lock-alt'></i>
            </div>
            
            <!-- <div class="pass-link">
                <a href="forgotpassword.html">Forgot Password ?</a>
            </div> -->

            <div class="field btn">
                <div class="btn-layer"></div>
                <input type="submit" name="submit" value="LOGIN">
            </div>
        </form>


                    
            </div>
        </div>
    
    <script src="login2.js"></script>
</body>
</html>
