<?php
require_once '../app/models/UserModel.php'; // Include the UserModel class

// Initialize response variables
$message = '';
$error = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Trim and sanitize input data
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirmPassword']);
    $phone = trim($_POST['phone']);
    $gender = trim($_POST['gender']);
    $email = trim($_POST['email']);
    $fullname = trim($_POST['fullname']);
    $dob = trim($_POST['dob']);
    $nid = trim($_POST['nid']);
    $profilePhoto = ''; // You can handle file uploads separately
    $userType = 'user'; // Default user type

    // Basic validations
    if (empty($username) || empty($password) || empty($confirmPassword) || empty($phone) || empty($gender) || empty($email)) {
        $error = "All fields are required.";
    } elseif ($password !== $confirmPassword) {
        $error = "Passwords do not match.";
    } else {
        // Instantiate UserModel
        $user = new UserModel();

        // Register the user
        $registrationResult = $user->registerUser($username, $password, $phone, $gender, $email);

        if ($registrationResult === "User registered successfully.") {
            // Get the newly registered user's ID
            $userId = $user->getUserIdByUsername($username);

            if ($userId) {
                // Add user details
                $detailsResult = $user->addUserDetails($userId, $fullname, $dob, $nid, $profilePhoto, $userType);

                if ($detailsResult === "User details added successfully.") {
                    $message = "Registration successful! You can now log in.";
                } else {
                    $error = $detailsResult; // Error adding user details
                }
            } else {
                $error = "Failed to retrieve user ID.";
            }
        } else {
            $error = $registrationResult; // Error during registration
        }
    }
}
?>

<!doctype html>
<html lang="en" class="deeppurple-theme">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, viewport-fit=cover, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="Maxartkiller">

    <title>Signup · Bachelor Hub</title>

    <!-- Material design icons CSS -->
    <link rel="stylesheet" href="vendor/materializeicon/material-icons.css">

    <!-- Roboto fonts CSS -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&amp;display=swap" rel="stylesheet">

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap-4.4.1/css/bootstrap.min.css" rel="stylesheet">

    <!-- Swiper CSS -->
    <link href="vendor/swiper/css/swiper.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">

    <script>
        function validateForm(event) {
            event.preventDefault(); // Prevent form submission for validation

            // Get form values
            const username = document.getElementById('username').value.trim();
            const phone = document.getElementById('phone').value.trim();
            const email = document.getElementById('inputEmail').value.trim();
            const password = document.getElementById('inputPassword').value;
            const confirmPassword = document.getElementById('confirmPassword').value;

            // Regular expressions
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const phonePattern = /^[0-9]{11}$/; // Assuming 11 digits for phone number

            // Validation
            if (!username) {
                alert('Username is required.');
                return;
            }

            if (!phone || !phonePattern.test(phone)) {
                alert('Enter a valid 11-digit phone number.');
                return;
            }

            if (!email || !emailPattern.test(email)) {
                alert('Enter a valid email address.');
                return;
            }

            if (!password) {
                alert('Password is required.');
                return;
            }

            if (password !== confirmPassword) {
                alert('Passwords do not match.');
                return;
            }

            document.querySelector('form').submit(); // Submit the form if validation passes
        }
    </script>
</head>
<body>
    <!-- Loader -->
    <div class="row no-gutters vh-100 loader-screen">
        <div class="col align-self-center text-white text-center">
            <img src="img/logo.png" alt="logo">
            <h1 class="mt-3"><span class="font-weight-light ">Bachelor</span>Hub</h1>
            <p class="text-mute text-uppercase small"></p>
            <div class="laoderhorizontal">
                <div></div>
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
    <!-- Loader ends -->

    <div class="wrapper">
        <!-- header -->
        <div class="header">
            <div class="row no-gutters">
                <div class="col-auto">
                    <a href="introduction.html" class="btn  btn-link text-dark"><i class="material-icons">chevron_left</i></a>
                </div>
                <div class="col text-center"></div>
                <div class="col-auto">
                </div>
            </div>
        </div>
        <!-- header ends -->

        <div class="row no-gutters login-row">
            <div class="col align-self-center px-3 text-center">
                <br>
                <img src="img/logo-login.png" alt="logo" class="logo-small">
                <h2><?php echo $message; ?></h2>
                <form class="form-signin mt-3" method="POST" onsubmit="validateForm(event)">
                    <div class="form-group">
                        <input type="text" id="username" name="username" class="form-control form-control-lg text-center" placeholder="Username" required autofocus>
                    </div>
                    <div class="form-group">
                        <input type="number" id="phone" name="phone" class="form-control form-control-lg text-center" placeholder="Phone Number" required>
                    </div>
                    <div class="form-group">
                        <input type="email" id="inputEmail" name="email" class="form-control form-control-lg text-center" placeholder="Email" required>
                    </div>
                    <div class="form-group">
                        <input type="password" id="inputPassword" name="password" class="form-control form-control-lg text-center" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <input type="password" id="confirmPassword" name="confirmPassword" class="form-control form-control-lg text-center" placeholder="Confirm Password" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="gender" name="gender" class="form-control form-control-lg text-center" placeholder="Gender" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="fullname" name="fullname" class="form-control form-control-lg text-center" placeholder="Full Name" required>
                    </div>
                    <div class="form-group">
                        <input type="date" id="dob" name="dob" class="form-control form-control-lg text-center" placeholder="Date of Birth" required>
                    </div>
                    <div class="form-group">
                        <input type="text" id="nid" name="nid" class="form-control form-control-lg text-center" placeholder="National ID" required>
                    </div>
                    <p class="mt-4 d-block text-secondary">
                        By clicking register, you are agreeing to the
                        <a href="javascript:void(0)">Terms and Conditions.</a>
                    </p>
                    <button type="submit" class="btn btn-default btn-lg btn-rounded shadow btn-block">Sign Up</button>
                </form>
                <a href="login" class="mt-4 d-block">Already Have an Account?</a>
            </div>
        </div>
    </div>

    <!-- jquery, popper and bootstrap js -->
    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="vendor/bootstrap-4.4.1/js/bootstrap.min.js"></script>

    <!-- swiper js -->
    <script src="vendor/swiper/js/swiper.min.js"></script>

    <!-- cookie js -->
    <script src="vendor/cookie/jquery.cookie.js"></script>

    <!-- template custom js -->
    <script src="js/main.js"></script>
</body>
</html>