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
    $totalNewOrder = get_total_new_orders_count($connect);
    if ( isset($_GET['customer-id'])) {
        $customerId = (int) $_GET['customer-id'];
        $customerData = get_customer_info_by_id($connect,$customerId);
        $creationDate = $customerData['creation_date'];
        $creationTime = explode(":",$customerData['creation_time']);
        $creationTime = $creationTime[0]."hr : ".$creationTime[1]."min : ".$creationTime[2]."sec";
        $username = $customerData['customer_username'];
        $password = $customerData['customer_password'];
        $recoveryEmail = $customerData['recovery_email'];
        $totalOrder = get_total_order_by_customer_id($connect,$customerId);
        $totalOrderedGames = count(get_ordered_game_list_by_customer_id($connect,$customerId));
        $orderedGameList = get_ordered_game_list_by_customer_id($connect,$customerId);
        echo count($orderedGameList);
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
    <!-- <link rel="stylesheet" href="styles/order-style.css"> -->
    <link rel="stylesheet" href="styles/customer-detail-style.css">
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
        <div class="title">Customer Detail</div>
        <div class="customer-detail-wrapper">
            <div class="customer-username"><?php echo $username;?></div>
            <div class="customer-id">customer-id: <?php echo $customerId;?></div>
            <div class="detail-wrapper">
                <div class="detail">
                    <div class="type">Account Creation Date</div>
                    <div class="value"><?php echo $creationDate;?></div>
                </div>
                <div class="detail">
                    <div class="type">Account Creation Time</div>
                    <div class="value"><?php echo $creationTime;?></div>
                </div>
                <div class="detail">
                    <div class="type">Total Order</div>
                    <div class="value"><?php echo $totalOrder;?> orders</div>
                </div>
                <div class="detail">
                    <div class="type">Total Ordered Games</div>
                    <div class="value"><?php echo $totalOrderedGames;?> games</div>
                </div>
                <div class="detail">
                    <div class="type">Amount Spent</div>
                    <div class="value"><?php echo $totalOrderedGames*$unitPrice;?> MMK</div>
                </div>
                <div class="detail">
                    <div class="type">Password</div>
                    <div class="value"><?php echo $password;?></div>
                </div>
            </div>



            <div class="login-history-title"><i class="fa-solid fa-clock-rotate-left"></i> Login History</div>
            <table class="customer-table" cellspacing="0">
                <tr>
                    <th class="login-date">Login Date</th>
                    <th class="login-time">Login Time</th>
                    <th class="ip-address">IP Address</th>
                    <th class="login-password">Login Password</th>
                    <th class="status">Status</th>
                    <th class="device">Device</th>
                </tr>
                <tr>
                    <td>22-Jan-2024</td>
                    <td>1hr : 20min : 22sec AM</td>
                    <td>1.32.44.232</td>
                    <td>blacksky!@</td>
                    <td class="status success-status"><i class="fa-regular fa-circle-check"></i> Success</td>
                    <!-- <td class="status failed-status"><i class="fa-regular fa-circle-xmark"></i> Failed</td> -->
                    <td>Mozilla Firefox (User-agent 2.0: Starcity-myanmar chrome base browser)</td>
                </tr>
                <tr>
                    <td>22-Jan-2024</td>
                    <td>1hr : 20min : 22sec AM</td>
                    <td>1.32.44.232</td>
                    <td>blacksky!@</td>
                    <td class="status success-status"><i class="fa-regular fa-circle-check"></i> Success</td>
                    <!-- <td class="status failed-status"><i class="fa-regular fa-circle-xmark"></i> Failed</td> -->
                    <td>Mozilla Firefox (User-agent 2.0: Starcity-myanmar chrome base browser)</td>
                </tr>
                <tr>
                    <td>22-Jan-2024</td>
                    <td>1hr : 20min : 22sec AM</td>
                    <td>1.32.44.232</td>
                    <td>blacksky!@</td>
                    <td class="status success-status"><i class="fa-regular fa-circle-check"></i> Success</td>
                    <!-- <td class="status failed-status"><i class="fa-regular fa-circle-xmark"></i> Failed</td> -->
                    <td>Mozilla Firefox (User-agent 2.0: Starcity-myanmar chrome base browser)</td>
                </tr>
                <tr>
                    <td>22-Jan-2024</td>
                    <td>1hr : 20min : 22sec AM</td>
                    <td>1.32.44.232</td>
                    <td>blacksky!@</td>
                    <td class="status success-status"><i class="fa-regular fa-circle-check"></i> Success</td>
                    <!-- <td class="status failed-status"><i class="fa-regular fa-circle-xmark"></i> Failed</td> -->
                    <td>Mozilla Firefox (User-agent 2.0: Starcity-myanmar chrome base browser)</td>
                </tr>
                <tr>
                    <td>22-Jan-2024</td>
                    <td>1hr : 20min : 22sec AM</td>
                    <td>1.32.44.232</td>
                    <td>blacksky!@</td>
                    <td class="status success-status"><i class="fa-regular fa-circle-check"></i> Success</td>
                    <!-- <td class="status failed-status"><i class="fa-regular fa-circle-xmark"></i> Failed</td> -->
                    <td>Mozilla Firefox (User-agent 2.0: Starcity-myanmar chrome base browser)</td>
                </tr>
            
    
            </table>

            <div class="ordered-game-list-title"><i class="fa-solid fa-list-check"></i> Ordered Game List</div>
            <table class="game-list-table" cellspacing="0">
                <tr>
                    <th>Image</th>
                    <th>Title</th>
                    <th>Genre</th>
                    <th>Download Size</th>
                </tr>
                <?php
                    foreach ( $orderedGameList as $gameId ) {
                        $gameData = get_game_info($connect,$gameId);
                        ?>
                            <tr>
                                <td><img src="<?php echo $gameData['profile_image'];?>" class="game-image"></td>
                                <td class="game-title"><?php echo $gameData['title'];?></td>
                                <td>action, fighting, strategy</td>
                                <td><?php echo $gameData['download_size'];?> GB</td>
                            </tr>
                        <?php
                    }
                ?>  
                <!-- <tr>
                    <td><img src="https://i.ibb.co/tKVG48D/Far-Cry-6-Logo.jpg" class="game-image"></td>
                    <td class="game-title">Far Cry 6</td>
                    <td>action, fighting, strategy</td>
                    <td>45 GB</td>
                </tr>
                <tr>
                    <td><img src="https://i.ibb.co/MpKQZY2/Lego-Star-Wars-The-Sky-Walker-Saga-Logo.jpg" class="game-image"></td>
                    <td class="game-title">LEGO Star Wars: The Skywalker Saga</td>
                    <td>action, fighting, strategy</td>
                    <td>45 GB</td>
                </tr> -->
            
    
            </table>
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