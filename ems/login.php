<?php 
session_start();
include("php/config.php");

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($con, $_POST['Email']);
    $password = mysqli_real_escape_string($con, $_POST['Password']);

    $result = mysqli_query($con, "SELECT * FROM kyliescustomers WHERE Email='$email'") or die("Select Error");
    $row = mysqli_fetch_assoc($result);

    if (is_array($row) && password_verify($password, $row['Password'])) {
        $_SESSION['Email'] = $row['Email'];
        $_SESSION['Username'] = $row['Username'];
        $_SESSION['loggedin'] = true;
        header("Location: ./CUSTOMER/cushome.php");
        exit();
    } else {
        $_SESSION['error'] = "Your Email or Password is invalid.";
        header("Location: Invalidlogin-p.html");
        exit();
    }
}

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $mobile = $_POST['mobile'];
    $password = $_POST['password'];
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);


    $verify_query = mysqli_query($con, "SELECT Email FROM kyliescustomers WHERE Email='$email'");

    if (mysqli_num_rows($verify_query) != 0) {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'This email is already in use. Try another one!',
                    showConfirmButton: true
                }).then(() => {
                    window.history.back();
                });
            });
        </script>";
    } else {
        $stmt = $con->prepare("INSERT INTO kyliescustomers (Username, Email, Address, MobileNumber, Password) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $email, $address, $mobile, $hashedPassword);

        if ($stmt->execute()) {
            header("Location: login.php?updated=true#login");
            exit();
        } else {
            echo "<script>
                alert('Registration failed! Please try again later.');
                window.history.back();
            </script>";
        }

        $stmt->close();
    }
    if ($stmt->execute()) {
    // ✅ Insert admin dashboard notification
    $adminMessage = "New customer registered: $username";
    mysqli_query($con, "INSERT INTO notifications (type, message, created_at) VALUES ('customer', '$adminMessage', NOW())");

    header("Location: login.php?updated=true#login");
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
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <title>LOGIN</title>
</head>
<body>
    <div class="container">
        <div class="form-box login">
            <form action="login.php" method="POST" class="login">
                <h1>LOGIN</h1>
                <div class="input-box">
                    <input type="text" id="Email" name="Email" placeholder="Email Address" required>
                    <i class='bx bx-user'></i>
                </div>
                <div class="input-box">
                    <input type="password" id="Password" name="Password" placeholder="Password" required>
                    <span id="togglePassword">
                        <i class="fa-regular fa-eye-slash"></i>
                    </span>
                    <i class='bx bx-lock-alt'></i>
                </div>
                <div class="captcha">
                    <div class="preview"></div>
                    <div class="captcha-form">
                        <input type="text" id="captcha-input" placeholder="Enter Captcha" required>
                        <button type="button" class="captcha-refresh">
                            <i class='bx bx-refresh'></i>
                        </button>
                    </div>
                </div>
                <div class="forgot-link">
                    <a href="forgotpassword.php">Forgot Password?</a>
                </div>
                <button type="submit" class="btn" id="login-btn" name="submit" value="Login">Login</button>
            </form>
        </div>

        <div class="form-box register">
            <form action="login.php" method="POST">
                <h1>REGISTRATION</h1>
                <div class="input-box">
                    <input type="text" name="username" id="username" autocomplete="off" placeholder="Username" required>
                    <i class='bx bx-user'></i>
                </div>
                <div class="input-box">
                    <input type="text" name="email" id="email" autocomplete="off" placeholder="Email" required>
                    <i class='bx bx-envelope'></i>
                </div>
                <div class="input-box">
                    <span style="position: absolute; margin-top: -20px; margin-left: 10px; font-size: 12px; color:#021e51;">(Put your House No.)</span>
                <input type="text" name="address" id="address" placeholder="Click the map to select address and optionally type house number" required />
                <i class='bx bxs-map'></i>
                 </div>

                <div class="map-popup" id="mapPopup">
                    <div id="leaflet-map" style="height: 240px; border-radius: 10px; position:relative; margin-top:-30px;"></div>
                </div>
                <!-- <div class="input-box">
                    <input type="text" name="address" id="address" autocomplete="off" placeholder="Address" required>
                    <i class='bx bxs-location-plus'></i>
                </div> -->
                <div class="input-box">
                    <input type="text" name="mobile" id="mobile" autocomplete="off" placeholder="MobileNumber" required>
                    <i class='bx bxs-phone'></i>
                </div>
                <div class="input-box">
                    <input type="password" name="password" id="password" placeholder="Password" required>
                    <span id="togglePassword">
                        <i class="fa-regular fa-eye-slash"></i>
                    </span>
                    <i class='bx bx-lock-alt'></i>
                </div>
                <button type="submit" class="btn" name="register" value="Register">Register</button>
            </form>
        </div>

        <div class="toggle-box">
            <div class="toggle-panel toggle-left">
                <h1>Hello, Welcome!</h1>
                <p>Don't have an account?</p>
                <button class="btn register-btn">Register</button>
            </div>
            <div class="toggle-panel toggle-right">
                <h1>Welcome Back!</h1>
                <p>Already have an account?</p>
                <button class="btn login-btn">Login</button>
            </div>
        </div>
    </div>

    <?php 
    if (isset($_GET['updated']) && $_GET['updated'] === 'true') {
        echo "<script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>";
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Registration Successful!',
                    showConfirmButton: false,
                    timer: 2000
                });
                setTimeout(function() {
                    document.querySelector('.container').classList.remove('active');
                }, 1500);
            });
        </script>";
    }
    ?>

    <script src="login2.js"></script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-pip@latest/leaflet-pip.min.js"></script>


<script>
document.addEventListener("DOMContentLoaded", function () {
    const stationLat = 14.6911004;
    const stationLng = 121.0566802;
    const map = L.map('leaflet-map').setView([stationLat, stationLng], 16);
    const marker = L.marker([stationLat, stationLng], { draggable: true }).addTo(map);
    const addressInput = document.getElementById("address");
    const mapPopup = document.getElementById("mapPopup");

    // Show the map popup
    addressInput.addEventListener("click", function () {
        const rect = addressInput.getBoundingClientRect();
        mapPopup.style.display = "block";
        mapPopup.style.marginRight = "200px";
        mapPopup.style.marginBottom = "100px";
        map.invalidateSize(); // Important if map was hidden before
    });

    // Optional: hide if click outside
    document.addEventListener("click", function (e) {
        if (!mapPopup.contains(e.target) && e.target !== addressInput) {
            mapPopup.style.display = "none";
        }
    });

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Convert delivery area to Leaflet format
    

const deliveryGeoJson = L.geoJSON({
    type: "Feature",
    geometry: {
        type: "Polygon",
        coordinates: [[
            [121.0556674, 14.6931585],
            [121.0556395, 14.6931356],
            [121.0554571, 14.6882552],
            [121.0564871, 14.6880995],
            [121.0567768, 14.6885252],
            [121.0578067, 14.6896882],
            [121.0579891, 14.6926164],
            [121.0556674, 14.6931585]
        ]]
    }
}, {
    style: {
        color: 'blue',
        fillOpacity: 0.2
    }
}).addTo(map);


    function updateAddress(lat, lon) {
    fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json&addressdetails=1`)
        .then(res => res.json())
        .then(data => {
            const address = data.address; // ✅ Fix: get address from API response
            const components = {
                houseNumber: address.house_number || '',
                street: address.road || '',
                barangay: address.suburb || address.village || '',
                city: address.city || address.town || '',
            };

        const formatted = [
            components.houseNumber,
            components.street,
            components.barangay,
            components.city
        ].filter(Boolean).join(', ');

            addressInput.value = formatted;
        })
        .catch(() => {
            addressInput.value = "Unable to fetch address";
        });
}


    function isInsideDeliveryArea(latlng) {
        return leafletPip.pointInLayer([latlng.lng, latlng.lat], deliveryGeoJson).length > 0;
    }




    map.on("click", function (e) {
        const { lat, lng } = e.latlng;
        if (isInsideDeliveryArea(e.latlng)) {
            marker.setLatLng([lat, lng]);
            updateAddress(lat, lng);
        } else {
            alert("❌ This location is outside the delivery area.");
        }
    });

    marker.on("dragend", function (e) {
        const { lat, lng } = e.target.getLatLng();
        if (isInsideDeliveryArea({ lat, lng })) {
            updateAddress(lat, lng);
        } else {
            alert("❌ This location is outside the delivery area.");
            marker.setLatLng([stationLat, stationLng]); // reset to center
            updateAddress(stationLat, stationLng);
        }
    });

    updateAddress(stationLat, stationLng);
});
</script>

</body>
</html>
