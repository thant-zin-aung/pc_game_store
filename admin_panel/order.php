<?php
    session_start();
    include('db-connect.php');
    include('db-executions.php');

    if ( isset($_SESSION['is_admin_logged_in']) ) {
        $adminUsername = $_SESSION['admin_username'];
    } else {
        header("location: login.php");
    }

    $orderList = get_order_info_list($connect,30);
    $totalNewOrder = get_total_new_orders_count($connect);
    $orderStatusList = get_order_status_list($connect);
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
    <link rel="stylesheet" href="styles/order-style.css">
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
            <div class="order-tab tab active-tab"><span><i class="fa-solid fa-money-bills"></i> Orders</span> <div class="new-order-number" style="display: <?php echo $totalNewOrder== 0 ? "none" : "flex"?>;"><?php echo $totalNewOrder;?></div></div>
            <div class="customer-tab tab"><span><i class="fa-solid fa-users"></i> Customers</span></div>
            <div class="game-list-tab tab"><span><i class="fa-solid fa-list"></i> Game list</span></div>
            <div class="admin-control-tab tab"><span><i class="fa-solid fa-user-tie"></i> Admin Control</span></div>
            <div class="image-uploader-tab tab"><span><i class="fa-regular fa-file-image"></i> Image Uploader</span></div>
            <div class="log-out-tab tab"><span><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</span></div>
        </div>
    </nav>


    <div class="main-wrapper">
        <div class="title">Order History <sup>( <span class="approved">Approved - <?php echo $orderStatusList['approved'];?></span> | <span class="declied">Declied - <?php echo $orderStatusList['declined'];?></span> | <span class="pending">Pending - <?php echo $orderStatusList['pending'];?></span> )</sup></div>
        <!-- <div class="title">Order History <sup>( <span class="pending">Pending - 7</span> )</sup></div> -->
        <!-- <div class="box-wrapper">
            <div class="box">
                <div class="float-text">check revenue list</div>
                <div class="icon-wrapper"><i class="fa-solid fa-sack-dollar"></i></div>
                <div class="title-wrapper">Total Revenue</div>
                <div class="number-wrapper">45000 MMK</div>
                <div class="sub-text">new orders revenue are not included</div>
            </div>
            <div class="box">
                <div class="float-text">check order history</div>
                <div class="icon-wrapper"><i class="fa-solid fa-bag-shopping"></i></div>
                <div class="title-wrapper">Total Order</div>
                <div class="number-wrapper">153 orders <span class="new-order">( +3 new orders )</span></div>
                <div class="sub-text">new orders are not included in total</div>
            </div>
            <div class="box">
                <div class="float-text">check customer list</div>
                <div class="icon-wrapper"><i class="fa-solid fa-users"></i></div>
                <div class="title-wrapper">Total Customer</div>
                <div class="number-wrapper">52 customers</div>
                <div class="sub-text">total number of customer</div>
            </div>
            <div class="box">
                <div class="float-text">check game list</div>
                <div class="icon-wrapper"><i class="fa-solid fa-sack-dollar"></i></div>
                <div class="title-wrapper">Total Game</div>
                <div class="number-wrapper">213 games</div>
                <div class="sub-text">new added number of games</div>
            </div>
        </div> -->

        <!-- <div class="recent-orders-title">Recent Orders</div> -->
        <table class="order-table" cellspacing="0">
            <tr>
                <th># Order ID</th>
                <th>Order Date</th>
                <th>Order Time</th>
                <th>Customer Username</th>
                <th>Total Games</th>
                <th>Total Amount</th>
                <th>Payment Type</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
                foreach($orderList as $orderData) {
                    $customerUsername = get_customer_info_by_id($connect,$orderData['customer_id'])['customer_username'];
                    $paymentLogoPath = "payment-logos/".($orderData['payment_type']=="kbzpay"?"kbzpay.png":"wavepay.png");
                    ?>
                    <tr>
                        <td>BPGS-<?php echo $orderData['id'];?></td>
                        <td><?php echo $orderData['order_date'];?></td>
                        <td><?php echo $orderData['order_time'];?></td>
                        <td><?php echo $customerUsername;?></td>
                        <td class="total-game"><span><?php echo $orderData['total_game'];?></span> Games</td>
                        <td class="total-amount"><span><?php echo $orderData['total_amount'];?></span> MMK</td>
                        <td class="payment-type"><div class="payment-type-wrapper"><img src="<?php echo $paymentLogoPath;?>"><?php echo $orderData['payment_type_long_form'];?></div></td>
                        <td style="display: <?php echo $orderData['order_status']=="approved" ? "table-cell" : "none";?>"><div class="status"><i class="fa-regular fa-circle-check"></i> Approved</div></td>
                        <td style="display: <?php echo $orderData['order_status']=="pending" ? "table-cell" : "none";?>"><div class="status pending-status"><i class="fa-solid fa-clock"></i> Pending</div></td>
                        <td style="display: <?php echo $orderData['order_status']=="declined" ? "table-cell" : "none";?>"><div class="status declied-status"><i class="fa-regular fa-circle-xmark"></i> Declied</div></td>
                        <td><button class="view-detail-button" order-id="<?php echo $orderData['id'];?>">View Details <i class="fa-solid fa-angles-right"></i></button></td>
                    </tr>
                    <?php
                }
            ?>
            <!-- <tr>
                <td>BPGS-1</td>
                <td>10-Dec-2023</td>
                <td>12:34 PM</td>
                <td>blacksky00797</td>
                <td class="total-game"><span>5</span> Games</td>
                <td class="total-amount"><span>5000</span> MMK</td>
                <td class="payment-type"><div class="payment-type-wrapper"><img src="https://play-lh.googleusercontent.com/cnKJYzzHFAE5ZRepCsGVhv7ZnoDfK8Wu5z6lMefeT-45fTNfUblK_gF3JyW5VZsjFc4">KBZ Pay</div></td>
                <td><div class="status"><i class="fa-regular fa-circle-check"></i> Approved</div></td>
                <td><button class="view-detail-button">View Details <i class="fa-solid fa-angles-right"></i></button></td>
            </tr> -->

        </table>

        <div class="copyright-wrapper">
            Copyright &copy; 2023. All rights reserved by &nbsp;<span>Blacksky PC Game Store</span>.
        </div>
    </div>
    
    <script src="scripts/order-script.js"></script>
    <script src="scripts/app-script.js"></script>
    <script src="scripts/tab-navigator.js"></script>
</body>
</html>