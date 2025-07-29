<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>REGISTER</title>
</head>
<body>
    <div class="container">
        <div class="box form-box">

        <?php 
        include("php/config.php");

        if(isset($_POST['submit'])){ 
            $username = $_POST['username'];
            $email = $_POST['email'];
            $mobile = $_POST['mobile'];
            $address = $_POST['address'];
            $password = $_POST['password'];

            // Check if email is unique
            $verify_query = mysqli_query($con, "SELECT Email FROM kyliescustomers WHERE Email='$email'");

            if(mysqli_num_rows($verify_query) != 0) {
                echo "<div class='message'>
                        <p>This email is already in use. Try another one!</p>
                      </div> <br>";
                echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button></a>";
            } else {
                // Insert new user data into the database
                // Updated query: change 'Mobile Number' to 'Mobile'
                $stmt = $con->prepare("INSERT INTO kyliescustomers (Username, Email, MobileNumber, Address, Password) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param("sssss", $username, $email, $mobile, $address, $password);

                if ($stmt->execute()) {
                    echo "<div class='message'>
                            <p>Registration Successful!</p>
                          </div> <br>";
                    echo "<a href='login.php'><button class='btn'>Login Now</button></a>";
                } else {
                    echo "<div class='message'>
                            <p>Registration failed! Please try again later.</p>
                          </div> <br>";
                    echo "<a href='javascript:self.history.back()'><button class='btn'>Go Back</button></a>";
                }

                $stmt->close();
            }
        }
        ?>

        <header>Sign Up</header>
        <form action="" method="post">
            <div class="field input">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" autocomplete="off" required>
            </div>
            
            <div class="field input">
                <label for="email">Email</label>
                <input type="text" name="email" id="email" autocomplete="off" required>
            </div>

            <div class="field input">
                <label for="mobile">Mobile Number</label>
                <input type="text" name="mobile" id="mobile" autocomplete="off" required>
            </div>

            <div class="field input">
                <label for="address">Address</label>
                <input type="text" name="address" id="address" autocomplete="off" required>
            </div>

            <div class="field input">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" autocomplete="off" required>
            </div>
           
            <div class="field">
                <input type="submit" class="btn" name="submit" value="Register" required>
            </div>
            <div class="links">
                Already a member? <a href="login.php">Sign In</a>
            </div>
        </form>
        </div>
    </div>
</body>
</html>
