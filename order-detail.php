<?php
    session_start();
    include('admin_panel/db-connect.php');
    include('admin_panel/db-executions.php');
    $isUserLoggedIn = false;
    if ( isset($_SESSION['is-user-logged-in']) ) {
        $isUserLoggedIn = true;
        $loginUsername = $_SESSION['login-username'];
        $cartItemList = get_cart_item_list($connect,$loginUsername);
    } else {
        header("locations: index.php");
    }

    if ( isset($_GET['order-id']) ) {
        $orderId = (int)$_GET['order-id'];
        $orderData = get_order_info_by_order_id($connect,$orderId);
        $customerUsername = get_customer_info_by_id($connect,$orderData['customer_id'])['customer_username'];
        $paymentLogoPath = "admin_panel/payment-logos/".($orderData['payment_type']=="kbzpay"?"kbzpay.png":"wavepay.png");
        $orderGameList = get_ordered_game_list_by_order_id($connect,$orderId);
        $screenshotImagePath = $orderData['uploaded_image'];
        $totalNewOrder = get_total_new_orders_count($connect);
    }
    $availableGenreList = get_available_genre_list($connect);
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
    <nav class="mobile-nav">
        <div class="left-wrapper">
            <div class="page-logo-wrapper">
                <div class="left">
                    <i class="fa-solid fa-gamepad"></i>
                </div>
                <div class="right">
                    BLACKSKY <br><span>pc game store...</span>
                </div>
            </div>
        </div>
        <div class="right-wrapper">
            <form action="cart.php" method="GET" class="add-to-cart-form">
                <input type="hidden" name="blacksky-cart" value="" class="cart-item">
                <button class="add-to-cart-wrapper" type="submit">
                    <div><i class="fa-solid fa-cart-shopping"></i> cart </div>
                    <div class="total-game"><?php echo $isUserLoggedIn?count($cartItemList):"";?></div>
                </button>
            </form>
        </div>
        <div class="menu-button">
            <i class="fa-solid fa-bars"></i>
        </div>
        <div class="menu-close-button">
            <i class="fa-solid fa-xmark"></i>
        </div>
    </nav>
    <nav class="desktop-nav">
        <div class="relative-wrapper">
            <div class="left-wrapper">
                <div class="page-logo-wrapper">
                    <div class="left">
                        <i class="fa-solid fa-gamepad"></i>
                    </div>
                    <div class="right">
                        BLACKSKY <br><span>pc game store...</span>
                    </div>
                </div>
            </div>
            <div class="middle-wrapper">
                <div class="tab home-tab">Home</div>
                <div class="tab genre-tab">
                    Genre &nbsp; <i class="fa-solid fa-angle-down"></i>
                    <br>
                    <div class="genre-wrapper">
                        <?php
                            foreach($availableGenreList as $availableGenre) {
                                ?>
                                <div class="genre" genre-id="<?php echo $availableGenre['id'];?>"><?php echo $availableGenre['genre_type'];?></div>
                                <?php
                            }
                        ?>
                    </div>

                </div>
                <div class="tab online-tab">Online games</div>
                <div class="tab download-guide-tab" style="display: <?php echo $isUserLoggedIn?"inline-block":"none";?>;">Download Guide</div>
                <div class="tab contact-us-tab">Contact Us</div>
            </div>
            <div class="right-wrapper">
                <form action="cart.php" method="GET" class="add-to-cart-form">
                    <input type="hidden" name="blacksky-cart" value="" class="cart-item">
                    <button class="add-to-cart-wrapper" type="submit">
                        <div><i class="fa-solid fa-cart-shopping"></i> cart </div>
                        <div class="total-game"><?php echo $isUserLoggedIn?count($cartItemList):"";?></div>
                    </button>
                </form>
                <div class="search-bar-wrapper">
                    <i class="fa-solid fa-magnifying-glass search-icon"></i>
                    <input class="search-bar" type="text" placeholder="Search games by name..." spellcheck="false">
                </div>
                <div class="login-signup-wrapper" isUserLoggedIn="<?php echo $isUserLoggedIn?"yes":"no";?>">
                    <img src="https://images.unsplash.com/photo-1553481187-be93c21490a9?q=80&w=2670&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D">
                    <div class="label">
                        <?php
                            echo $isUserLoggedIn?$loginUsername:"Log in / Sign up";
                        ?>
                    </div>
                    <div class="account-info-wrapper">
                        <div class="my-game-library-tab">My game library</div>
                        <div class="order-history-tab">Order history</div>
                        <div class="log-out-tab"><i class="fa-solid fa-arrow-right-from-bracket"></i> &nbsp; Log Out</div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="screenshot-wrapper">
        <!-- <div class="title">Payment Screenshot</div> -->
        <img src="<?php echo $screenshotImagePath?>">
        <!-- <div class="button download-button"><i class="fa-regular fa-circle-down"></i> download</div> -->
        <div class="button close-button"><i class="fa-regular fa-circle-xmark"></i> close</div>
    </div>

    <div class="main-wrapper">
        <div class="order-detail-title">Order Details</div>
        <div class="order-detail-wrapper">
            <div class="total-game-wrapper">
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

            <div class="detail-wrapper">
                <!-- <div class="title">Details</div> -->
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
                        <div class="value"><button class="image-view-button"><i class="fa-solid fa-file-image"></i> View</button></div>
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
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" value="<?php echo $isUserLoggedIn?"yes":"no";?>" class="is-user-logged-in">

    <div class="copyright-wrapper">
            Copyright &copy; 2023. All rights reserved by &nbsp;<span>Blacksky PC Game Store</span>
    </div>
    
    <script src="scripts/app-script.js"></script>
    <script src="scripts/order-detail-script.js"></script>
    <script src="scripts/navigation.js"></script>
    <script src="scripts/add-to-cart.js"></script>
</body>
</html>