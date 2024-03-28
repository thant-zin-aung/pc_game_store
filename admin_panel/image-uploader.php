<?php
    session_start();
    include('db-connect.php');
    include('db-executions.php');

    if ( isset($_SESSION['is_admin_logged_in']) ) {
        $adminUsername = $_SESSION['admin_username'];
    } else {
        header("location: login.php");
    }

    $totalNewOrder = get_total_new_orders_count($connect);
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
    <!-- <link rel="stylesheet" href="styles/order-style.css"> -->
    <!-- <link rel="stylesheet" href="styles/game-list-style.css"> -->
    <link rel="stylesheet" href="styles/image-uploader.css">
</head>
<body>
    <nav>
        <div class="page-logo-wrapper">
            <div class="left">
                <i class="fa-solid fa-gamepad"></i>
            </div>
            <div class="right">
                BLACKSKY <br><span>pc game store...</span>
            </div>
        </div>
        <div class="login-user"><i class="fa-solid fa-unlock-keyhole"></i> Logged in as <u>blacksky</u> <br> <span>Admin</span></div>
        <div class="tab-wrapper">
            <div class="dashboard-tab tab"><span><i class="fa-solid fa-border-all"></i> Dashboard</span></div>
            <div class="order-tab tab"><span><i class="fa-solid fa-money-bills"></i> Orders</span> <div class="new-order-number" style="display: <?php echo $totalNewOrder== 0 ? "none" : "flex"?>;"><?php echo $totalNewOrder;?></div></div>
            <div class="customer-tab tab"><span><i class="fa-solid fa-users"></i> Customers</span></div>
            <div class="game-list-tab tab"><span><i class="fa-solid fa-list"></i> Game list</span></div>
            <div class="admin-control-tab tab"><span><i class="fa-solid fa-user-tie"></i> Admin Control</span></div>
            <div class="image-uploader-tab tab active-tab"><span><i class="fa-regular fa-file-image"></i> Image Uploader</span></div>
            <div class="log-out-tab tab"><span><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</span></div>
        </div>
    </nav>

    <div class="main-wrapper">
        <div class="title">Image Uploader Panel</div>
        <div class="uploader-wrapper">
            <div class="left-wrapper">
                <div class="input-wrapper">
                    <input type="file" id="image-files-selector" multiple>
                    <label for="image-files-selector">Select image files...</label>
                </div>
                <input type="text" placeholder="Enter game title" class="game-title-box" spellcheck="false">
                <div class="upload-button">Upload Images</div>
                <div class="wait-label"><i class="fa-solid fa-hourglass-start"></i> &nbsp; please wait while uploading...</div>
            </div>
            <div class="right-wrapper">
                <div class="output-link-title">Images Links</div>
                <textarea name="" disabled></textarea>
            </div>
        </div>

        <div class="copyright-wrapper">
            Copyright &copy; 2023. All rights reserved by &nbsp;<span>Blacksky PC Game Store</span>.
        </div>
    </div>
    
    <script src="scripts/app-script.js"></script>
    <script src="scripts/image-uploader.js"></script>
    <script src="scripts/tab-navigator.js"></script>
    
</body>
</html>