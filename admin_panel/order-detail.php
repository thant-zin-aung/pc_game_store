<?php
    session_start();
    include('db-connect.php');
    include('db-executions.php');

    if ( isset($_SESSION['is_admin_logged_in']) ) {
        $adminUsername = $_SESSION['admin_username'];
    } else {
        header("location: login.php");
    }
    
    $orderId = (int)$_GET['order-id'];
    if ( isset($_GET['approve-button']) ) {
        $approvalQuery = "UPDATE order_list SET order_status='approved' WHERE id=$orderId";
        $approvalQueryResult = mysqli_query($connect,$approvalQuery);
        if ( $approvalQueryResult ) {
            echo "<script>alert('Order was approved successfully...');</script>";
            header("location: order.php");
        } else {
            echo "<script>alert('Failed to update order status...');</script>";
        }
    }
    if ( isset($_GET['decline-button']) ) {
        $approvalQuery = "UPDATE order_list SET order_status='declined' WHERE id=$orderId";
        $approvalQueryResult = mysqli_query($connect,$approvalQuery);
        if ( $approvalQueryResult ) {
            echo "<script>alert('Order was declined...');</script>";
            header("location: order.php");
        } else {
            echo "<script>alert('Failed to update order status...');</script>";
        }
    }
    $orderData = get_order_info_by_order_id($connect,$orderId);
    $customerUsername = get_customer_info_by_id($connect,$orderData['customer_id'])['customer_username'];
    $paymentLogoPath = "payment-logos/".($orderData['payment_type']=="kbzpay"?"kbzpay.png":"wavepay.png");
    $orderGameList = get_ordered_game_list_by_order_id($connect,$orderId);
    $screenshotImagePath = str_replace("admin_panel/","",$orderData['uploaded_image']);
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
    <link rel="stylesheet" href="styles/order-detail-style.css">
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

    <div class="screenshot-wrapper">
        <!-- <div class="title">Payment Screenshot</div> -->
        <img src="<?php echo $screenshotImagePath?>">
        <div class="button download-button"><i class="fa-regular fa-circle-down"></i> download</div>
        <div class="button close-button"><i class="fa-regular fa-circle-xmark"></i> close</div>
    </div>

    <div class="main-wrapper">
        <div class="title">Order Details #BPGS-<?php echo $orderId;?></div>

        <div class="order-detail-wrapper">
            <div class="total-game-wrapper">
                <div class="title">Total games - <span><?php echo $orderData['total_game'];?></span></div>
                <?php
                    foreach ( $orderGameList as $gameId ) {
                        $gameData = get_game_info($connect,$gameId);
                        ?>
                            <div class="game-wrapper">
                                <img src="<?php echo $gameData['profile_image'];?>">
                                <div class="game-title"><?php echo $gameData['title'];?></div>
                            </div>
                        <?php
                    }
                ?>                
                <!-- <div class="game-wrapper">
                    <img src="https://i.ibb.co/tKVG48D/Far-Cry-6-Logo.jpg">
                    <div class="game-title">FAR CRY 6</div>
                </div>
                <div class="game-wrapper">
                    <img src="https://i.ibb.co/MpKQZY2/Lego-Star-Wars-The-Sky-Walker-Saga-Logo.jpg">
                    <div class="game-title">LEGO Star Wars: The Skywalker Saga</div>
                </div>
                <div class="game-wrapper">
                    <img src="https://i.ibb.co/B6XpSk8/Tales-Of-Arise-Logo.jpg">
                    <div class="game-title">Tales Of Arise</div>
                </div> -->
            </div>

            <form class="detail-wrapper" method="GET" action="order-detail.php">
                <div class="title">Details</div>
                <div class="list-wrapper">
                    <div class="detail">
                        <div class="type">Order ID</div>
                        <div class="value">BPGS-<?php echo $orderData['id'];?></div>
                    </div>
                    <div class="detail">
                        <div class="type">Order Date</div>
                        <div class="value"><?php echo $orderData['order_date'];?></div>
                    </div>
                    <div class="detail">
                        <div class="type">Order Time</div>
                        <div class="value"><?php echo $orderData['order_time'];?></div>
                    </div>
                    <div class="detail">
                        <div class="type">Customer Username	</div>
                        <div class="value"><?php echo $customerUsername;?></div>
                    </div>
                    <div class="detail">
                        <div class="type">Total Games</div>
                        <div class="value"><?php echo $orderData['total_game'];?> Games</div>
                    </div>
                    <div class="detail">
                        <div class="type">Total Amount</div>
                        <div class="value"><?php echo $orderData['total_amount'];?> MMK</div>
                    </div>
                    <div class="detail">
                        <div class="type">Payment Type</div>
                        <div class="value">
                            <div class="payment-wrapper">
                                <img src="<?php echo $paymentLogoPath;?>" alt="">
                                <?php echo $orderData['payment_type_long_form'];?>
                            </div>
                        </div>
                    </div>
                    <div class="detail">
                        <div class="type">Payment Process Method</div>
                        <div class="value"><?php echo $orderData['payment_process_method'];?></div>
                    </div>
                    <div class="detail">
                        <div class="type">Uploaded Image</div>
                        <div class="value"><div class="image-view-button"><i class="fa-solid fa-file-image"></i> View</div></div>
                    </div>
                    <div class="detail">
                        <div class="type">Transaction ID</div>
                        <div class="value"><?php echo $orderData['transaction_id'];?></div>
                    </div>
                    <div class="detail">
                        <div class="type">Status</div>
                        <div class="value">
                            <div style="display: <?php echo $orderData['order_status']=="approved" ? "block" : "none";?>" class="status approved-status"><i class="fa-regular fa-circle-check"></i> Approved</div>
                            <div style="display: <?php echo $orderData['order_status']=="pending" ? "block" : "none";?>" class="status pending-status"><i class="fa-solid fa-clock"></i> Pending</div>
                            <div style="display: <?php echo $orderData['order_status']=="declined" ? "block" : "none";?>" class="status declied-status"><i class="fa-regular fa-circle-xmark"></i> Declined</div>
                        </div>
                    </div>
                    <input type="hidden" name="order-id" value="<?php echo $orderId;?>">
                    <button class="submit-button approve-button" name="approve-button" type="submit" style="display: none;"><i class="fa-regular fa-circle-check"></i> Approve</button>
                    <button class="submit-button decline-button" name="decline-button" type="submit" style="display: none;"><i class="fa-regular fa-circle-xmark"></i> Decline</button>
                    <div class="submit-button edit-button"><i class="fa-regular fa-pen-to-square"></i> &nbsp; Edit Approval</div>
                </div>
            </form>
        </div>

        <div class="copyright-wrapper">
            Copyright &copy; 2023. All rights reserved by &nbsp;<span>Blacksky PC Game Store</span>.
        </div>
    </div>
    
    <script src="scripts/app-script.js"></script>
    <script src="scripts/order-detail-script.js"></script>
    <script src="scripts/tab-navigator.js"></script>
</body>
</html>