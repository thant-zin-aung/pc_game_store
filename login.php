<?php
    session_start();
    if ( isset($_SESSION['is-user-logged-in']) ) {
        header('location: index.php');
    }
    date_default_timezone_set("Asia/Yangon");
    include('admin_panel/db-connect.php');
    include('admin_panel/db-executions.php');
    if ( isset($_POST['sign-up-button']) ) {
        $username = $_POST['sign-up-username'];
        $password = $_POST['sign-up-password-1'];
        $confirmPassword = $_POST['sign-up-password-2'];
        $recoveryEmail = $_POST['recovery-email'];
        $creationDate = date("Y-m-d");
        $creationTime = date("H:i:s");
        if ($password !== $confirmPassword ) {
            echo "<script>alert('Warning!!! Password do not mactch...');</script>";
        } else if (check_if_customer_already_exists($connect,$username)) {
            echo "<script>alert('Warning!!! Username has been already taken...');</script>";
        } else {
            $addCustomerQuery = "INSERT INTO customer_list(customer_username,customer_password,recovery_email,creation_date,creation_time) VALUES(
                '$username','$password','$recoveryEmail','$creationDate','$creationTime'
            )";
            $addCustomerQueryResult = mysqli_query($connect,$addCustomerQuery);
            if ( $addCustomerQueryResult ) {
                echo "<script>alert('New customer added successfully...');</script>";
            } else {
                echo "<script>alert('Warning!!! Failed to add new customer...');</script>";
            }
        }
    } else if ( isset($_POST['login-button']) ) {
        $username = $_POST['login-username'];
        $password = $_POST['login-password'];
        $loginQuery = "SELECT * FROM customer_list WHERE customer_username='$username' AND customer_password='$password'";
        $adminLoginQuery = "SELECT * FROM admin_list WHERE admin_username='$username' AND admin_password='$password'";
        $loginQueryResult = mysqli_query($connect,$loginQuery);
        $adminLoginQueryResult = mysqli_query($connect,$adminLoginQuery);
        $isLoginCorrect = mysqli_num_rows($loginQueryResult) > 0 ? true : false;
        if ( $isLoginCorrect ) {
            $_SESSION['is-user-logged-in'] = true;
            $_SESSION['login-username'] = $username;
            echo "<script>window.location = 'index.php';</script>";
        } else {
            echo "<script>alert('Warning!!! Failed to log in ( username or password is not correct... )');</script>";
        }
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blacksky PC Game Store</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Megrim&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/e2c9faac31.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles/app-style.css">
    <link rel="stylesheet" href="styles/login-style.css">
</head>
<body style="background-image: url('images/login-wallpapers/3.jpg');">
    <div class="main-wrapper">
        <div class="login-signup-wrapper">
            <div class="header-wrapper">
                <div class="header login-header selected-form-design">LOGIN</div>
                <div class="header sign-up-header">SIGN UP</div>
            </div>
            <form action="login.php" method="POST" class="form-wrapper login-form-wrapper selected-form-design">
                <div>
                    <label for="login-username">Username</label><br>
                    <input type="text" id="login-username" class="login-username" name="login-username" spellcheck="false" autocomplete="off" autofocus required>
                </div>
                <div>
                    <label for="login-password">Password</label><br>
                    <input type="password" id="login-password" class="login-password" name="login-password" spellcheck="false" required>
                </div>
                <div class="forgot-password">Forgot password?</div>
                <div>
                    <input type="submit" value="LOGIN" name="login-button" class="login-sign-up-button login-button">
                </div>
            </form>
            <form action="login.php" method="POST" class="form-wrapper sign-up-form-wrapper">
                <div>
                    <label for="sign-up-username">Username</label><br>
                    <input type="text" id="sign-up-username" class="sign-up-username no-space-box" name="sign-up-username" spellcheck="false" autocomplete="off" autofocus required>
                </div>
                <div>
                    <label for="sign-up-password-1">Password</label><br>
                    <input type="password" id="sign-up-password-1" class="sign-up-password-1 no-space-box" name="sign-up-password-1" spellcheck="false" autofocus required>
                </div>
                <div>
                    <label for="sign-up-password-2">Confirm Password</label><br>
                    <input type="password" id="sign-up-password-2" class="sign-up-password-2 no-space-box" name="sign-up-password-2" spellcheck="false" autofocus required>
                </div>
                <div>
                    <label for="recovery-email">Email Address* (Optional)</label><br>
                    <input type="email" id="recovery-email" class="recovery-email" name="recovery-email" spellcheck="false"autocomplete="off" autofocus>
                </div>
                <div class="checkbox-wrapper">
                    <input type="checkbox" name="agree-checkbox" id="agree-checkbox" class="agree-checkbox" required>
                    <label for="agree-checkbox">I agree and accept the <a target="_blank" href="tos.html">Team of Service</a></label>
                </div>
                <div>
                    <input type="submit" value="SIGN UP" name="sign-up-button" class="login-sign-up-button sign-up-button">
                </div>
            </form>
        </div>
        <a href="index.php" class="back-to-home">Go back to home >>></a>
    </div>

    <div class="error-dialog-wrapper">
        <div class="error-dialog">
            <div class="logo"><i class="fa-regular fa-circle-xmark"></i></div>
            <div class="text">Spaces are not allowed.</div>
            <div class="ok-button">OK</div>
        </div>
    </div>



    <!-- <div class="copyright-wrapper">
        Copyright &copy; 2023. All rights reserved by <span>Blacksky PC Game Store</span>.
    </div> -->
    
    <!-- <script src="scripts/app-script.js"></script> -->
    <script src="scripts/login.js"></script>
</body>
</html>