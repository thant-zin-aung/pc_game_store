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
    
    $availableGenreList = get_available_genre_list($connect);
    $customerId = (int)get_customer_info_by_username($connect,$loginUsername)['id'];
    $purchasedGameList = get_purchased_game_list_by_customer_id($connect,$customerId);
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
        <link rel="stylesheet" href="styles/my-game-library.css">
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

    <!-- <div class="overlay" style="background-image: url('');"></div> -->

    <div class="my-game-library-wrapper">
        <div class="my-game-library-title">My Games</div>
        <div class="sale-wrapper">
            <?php
                foreach ( $purchasedGameList as $purchasedGame ) {
                    $gameId = $purchasedGame['id'];
                    $isOnline = $purchasedGame['is_online_game'] === "yes" ? true : false;
                    $isPurchased = true;
                    $posterImage = $purchasedGame['profile_image'];
                    $genre = "Fighting";
                    $gameTitle = $purchasedGame['title'];
                    $downloadSize = $purchasedGame['download_size'];
                    ?>
                    <div class="game-wrapper">
                        <div class="status-wrapper">
                            <div class="status online-status" style="display: <?php echo $isOnline?'block':'none';?>;">Online</div>
                            <div class="status purchased-status" style="display: <?php echo $isPurchased?'block':'none';?>;">Purchased</div>
                        </div>
                        <div class="game-image-wrapper">
                            <img src="<?php echo $posterImage;?>">
                            <div class="genre-wrapper">
                                <?php
                                    $genreArray = get_genre_of_game($connect,$gameId);
                                    $limitedGenreAmount = count($genreArray)>4 ? 4 : count($genreArray);
                                    for ( $genreCount = 0 ; $genreCount < $limitedGenreAmount ; $genreCount++ ) {
                                        $genreType = $genreArray[$genreCount]['genre_type'];
                                        ?>
                                            <div class="genre"><?php echo $genreType;?></div>
                                        <?php
                                    }
                                ?>
                                <!-- <div class="genre">shooting</div>
                                <div class="genre">fighting</div> -->
                            </div>
                        </div>
                        <div class="game-info-wrapper">
                            <div class="game-title"><?php echo $gameTitle;?></div>
                            <div class="game-spec">Download size: <span><?php echo $downloadSize;?> GB</span></div>
                        </div>
                        <div class="overlay-wrapper">
                            <div class="purchased-button"><i class="fa-solid fa-unlock"></i> Purchased</div>
                            <!-- <div class="add-to-cart-button" game-id="<?php echo $gameId;?>"><i class="fa-solid fa-cart-shopping" aria-hidden="true"></i> Add to cart</div> -->
                            <div class="check-game-detail-button" game-id="<?php echo $gameId;?>"><i class="fa-solid fa-circle-info"></i> Check game info</div>
                        </div>
                    </div>
                    <?php
                }
            ?>
        </div>
    </div>

    <input type="hidden" value="<?php echo $isUserLoggedIn?"yes":"no";?>" class="is-user-logged-in">
        
    <script src="scripts/app-script.js"></script>
    <script src="scripts/navigation.js"></script>
    <script src="scripts/add-to-cart.js"></script>
    <script src="scripts/my-game-library.js"></script>
    
</body>
</html>