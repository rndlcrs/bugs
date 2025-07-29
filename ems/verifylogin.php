<?php 
    session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="login.css">
    <title>LOGIN</title>
</head>
<?php

                include("php/config.php");
                if(isset($_POST['submit'])){
                    $email = mysqli_real_escape_string($con,$_POST['Email']);
                    $password = mysqli_real_escape_string($con,$_POST['Password']);

                    $result = mysqli_query($con,"SELECT * FROM kyliescustomers WHERE Email='$email' AND Password='$password'") or die("Select Error");
                    $row = mysqli_fetch_assoc($result);

                    if(is_array($row) && !empty($row)){
                        $_SESSION['Email'] = $row['Email'];
                        $_SESSION['Password'] = $row['Password'];
                    }else{
                        $conn->close();
                        header("Location: Invalidlogin-p.html"); // Stay on the login page
                        exit();
        
                    }
                    if(isset($_SESSION['valid'])){
                        header("Location: custprofile.php");
                    }
                    }else{}
                   

            ?>

<body>
    <div class="loginform">


            <div class="form-content">
                <form action="custprofile.php" method="POST" class="login">
                
                    <div class="field">
                        <input type="Email" id="Email" name="Email" placeholder="Email Address" required>
                    </div>
                    <div class="field">
                        <input type="Password" id="Password" name="Password" placeholder="Password" required>
                    </div>
                    <div class="pass-link">
                        <a href="forgotpassword.html">Forgot Password?</a>
                    </div>
                    <div class="field btn">
                        <div class="btn-layer">
                        <input type="submit" name="login_email" value="Login" required>
                    </div></div>
                   
                </form>

                
            </div>
        </div>
    </div>
</body>
</html>
