<?php
    session_start();
    include('db-connect.php');
    include('db-executions.php');
    include('app-config.php');
    if ( isset($_SESSION['is_admin_logged_in']) ) {
        $adminUsername = $_SESSION['admin_username'];
    } else {
        header("location: login.php");
    }
    global $unitPrice;
    $customerList = get_customer_list($connect,20);
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
    <link rel="stylesheet" href="styles/customer-style.css">
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
            <div class="customer-tab tab active-tab"><span><i class="fa-solid fa-users"></i> Customers</span></div>
            <div class="game-list-tab tab"><span><i class="fa-solid fa-list"></i> Game list</span></div>
            <div class="admin-control-tab tab"><span><i class="fa-solid fa-user-tie"></i> Admin Control</span></div>
            <div class="image-uploader-tab tab"><span><i class="fa-regular fa-file-image"></i> Image Uploader</span></div>
            <div class="log-out-tab tab"><span><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</span></div>
        </div>
    </nav>

    <div class="main-wrapper">
        <div class="title">Customer List</div>

        <table class="customer-table" cellspacing="0">
            <tr>
                <th># Customer ID</th>
                <th>Customer Username</th>
                <th>Customer Password</th>
                <th>Total Order</th>
                <th>Total Ordered Games</th>
                <th>Amount Spent</th>
                <th>Action</th>
            </tr>
            <?php
                foreach($customerList as $customerData) {
                        $totalOrder = get_total_order_by_customer_id($connect,(int)$customerData['id']);
                        $totalOrderedGames = count(get_ordered_game_list_by_customer_id($connect,(int)$customerData['id']));
                    ?>
                    <tr>
                        <td><?php echo $customerData['id'];?></td>
                        <td><?php echo $customerData['customer_username'];?></td>
                        <td><?php echo $customerData['customer_password'];?></td>
                        <td><?php echo $totalOrder;?> Orders</td>
                        <td><?php echo $totalOrderedGames;?> Games</td>
                        <td class="amount-spent"><span><?php echo $totalOrderedGames*$unitPrice;?></span> MMK</td>
                        <td><button class="view-detail-button" customer-id="<?php echo $customerData['id'];?>">View Details <i class="fa-solid fa-angles-right"></i></button></td>
                    </tr>
                    <?php
                }
            ?>
            <!-- <tr>
                <td>1</td>
                <td>blacksky00797</td>
                <td>blacksky123!@#</td>
                <td>4 Orders</td>
                <td>35 Games</td>
                <td class="amount-spent"><span>52500</span> MMK</td>
                <td><button class="view-detail-button">View Details <i class="fa-solid fa-angles-right"></i></button></td>
            </tr> -->
        </table>


        <div class="copyright-wrapper">
            Copyright &copy; 2023. All rights reserved by &nbsp;<span>Blacksky PC Game Store</span>.
        </div>
    </div>
    
    <script src="scripts/app-script.js"></script>
    <script src="scripts/order-detail-script.js"></script>
    <script src="scripts/customer-script.js"></script>
    <script src="scripts/tab-navigator.js"></script>
</body>
</html>