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
        header("location: index.php");
    }
    $customerId = (int)get_customer_info_by_username($connect,$loginUsername)['id'];
    $orderList = get_order_list_by_customer_id($connect,$customerId);
    $availableGenreList = get_available_genre_list($connect);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order History</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Megrim&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/e2c9faac31.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles/app-style.css">
    <link rel="stylesheet" href="styles/order-history.css">
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

    <div class="order-history-wrapper">
        <div class="order-history-title">
            Order History Table
        </div>
        <table class="order-history-table">
            <tr>
                <th>Order No.</th>
                <th>Order Date</th>
                <th>Order Time</th>
                <th>Total Game</th>
                <th>Total Price</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            <?php
                foreach ( $orderList as $orderData) {
                    ?>
                    <tr>
                        <td>BPGS-<?php echo $orderData['id'];?></td>
                        <td><?php echo $orderData['order_date'];?></td>
                        <td><?php echo $orderData['order_time'];?></td>
                        <td><?php echo $orderData['total_game'];?> Games</td>
                        <td><?php echo $orderData['total_amount'];?> MMK</td>
                        <td style="display: <?php echo $orderData['order_status']=='pending'?'table-cell':'none';?>;" class="status pending"><i class="fa-regular fa-hourglass-half"></i> pending</td>
                        <td style="display: <?php echo $orderData['order_status']=='approved'?'table-cell':'none';?>;" class="status approved"><i class="fa-regular fa-circle-check"></i> approved</td>
                        <td style="display: <?php echo $orderData['order_status']=='declined'?'table-cell':'none';?>;" class="status declined"><i class="fa-regular fa-circle-xmark"></i> declined</td>
                        <td><div class="detail-button" order-id="<?php echo $orderData['id'];?>">Detail>>></div></td>
                    </tr>
                    <?php
                }
            ?>
            <!-- <tr>
                <td>BPGS-2</td>
                <td>2024-Sep-22</td>
                <td>8hr:32min:22sec</td>
                <td>5 Games</td>
                <td>5000 MMK</td>
                <td class="status pending"><i class="fa-regular fa-hourglass-half"></i> &nbsp; pending</td>
                <td class="status approved"><i class="fa-regular fa-circle-check"></i> &nbsp; approved</td>
                <td class="status declined"><i class="fa-regular fa-circle-xmark"></i> &nbsp; declined</td>
                <td><div class="detail-button" order-id="">Detail>>></div></td>
            </tr> -->
            
        </table>
    </div>

    <input type="hidden" value="<?php echo $isUserLoggedIn?"yes":"no";?>" class="is-user-logged-in">

    <script src="scripts/app-script.js"></script>
    <script src="scripts/navigation.js"></script>
    <!-- <script src="scripts/server-script.js"></script> -->
    <script src="scripts/add-to-cart.js"></script>
    <script src="scripts/order-history.js"></script>
</body>
</html>