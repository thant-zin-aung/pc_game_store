
<?php
    session_start();
    include('db-connect.php');
    include('db-executions.php');
    include('app-config.php');
    if ( isset($_POST['login-button']) ) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $loginQuery = "SELECT * FROM admin_list WHERE admin_username='$username' AND admin_password='$password'";
        $loginQueryResult = mysqli_query($connect,$loginQuery);
        $totalResult = mysqli_num_rows($loginQueryResult);
        
        if ( $totalResult == 0 ) {
            echo "<script>alert('Failed to login. Username or password is wrong.');</script>";
        } else {
            $_SESSION['is_admin_logged_in'] = true;
            $_SESSION['admin_username'] = $username;
            header("location: index.php");
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
        <link rel="stylesheet" href="styles/login-style.css">
    </head>
<body>
    <div class="login-wrapper">
        <div class="title">Admin Login</div>
        <div class="sub-title">blacksky pc game store</div>
        <form action="login.php" method="POST">
            <input type="text" name="username" placeholder="Enter your username" spellcheck="false">
            <br>
            <input type="password" name="password" placeholder="Enter your password" spellcheck="false">
            <br>
            <button type="submit" name="login-button">LOGIN</button>
        </form>
    </div>
</body>
</html>