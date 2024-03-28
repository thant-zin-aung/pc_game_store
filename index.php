<?php
    session_start();
    include('admin_panel/db-connect.php');
    include('admin_panel/db-executions.php');
    $isUserLoggedIn = false;
    $browseGameLimit = 30;
    if ( isset($_SESSION['is-user-logged-in']) ) {
        $isUserLoggedIn = true;
        $loginUsername = $_SESSION['login-username'];
        $cartItemList = get_cart_item_list($connect,$loginUsername);
    }
    $customerId = $isUserLoggedIn?(int)get_customer_info_by_username($connect,$loginUsername)['id']:0;
    $genreQuery = "SELECT * FROM genre_list";
    $genreQueryResult = mysqli_query($connect,$genreQuery);
    $totalGenre = mysqli_num_rows($genreQueryResult);
    // Order is important....
    // Page pagination codes.....
    $totalGameQuery = "SELECT count(id) as total_game FROM game_list";
    $totalGameQueryResult = mysqli_query($connect,$totalGameQuery);
    $totalGame = mysqli_fetch_array($totalGameQueryResult)['total_game'];
    $totalPagination = ceil(($totalGame-5)/$browseGameLimit);
    $currentPaginationNumber = 1;
    if ( isset($_GET['page-number']) ) {
        $currentPaginationNumber = $_GET['page-number'] > $totalPagination ? $totalPagination : $_GET['page-number'];
    }
    $newReleaseGameArray = get_game_list($connect,1,5);
    // $browseGameStartingPoint = 1;
    $browseGameStartingPoint = ($currentPaginationNumber==1) ? 1 : $browseGameLimit*($currentPaginationNumber-1)+6;
    if ( ($totalGame - $browseGameStartingPoint) < $browseGameLimit ) {
        $browseGameLimit = $totalGame - $browseGameStartingPoint+1;
    }
    $browseGameArray = get_game_list($connect,$browseGameStartingPoint,$browseGameLimit);
    // Page pagination codes.....
    // Order is important....
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blacksky PC Game Store</title>
    <link rel="shortcut icon" href="images/page-logo/blacksky_pc_game_store.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Megrim&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/e2c9faac31.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles/app-style.css">
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
                            for ( $count = 0 ; $count < $totalGenre ; $count++ ) {
                                $genreData = mysqli_fetch_array($genreQueryResult);
                                $genreId = $genreData['id'];
                                $genreType = $genreData['genre_type'];
                                ?>
                                    <div class="genre" genre-id="<?php echo $genreId;?>"><?php echo $genreType;?></div>
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

    <div class="main-wrapper">
        <div class="latest-game-title">New Released Games</div>
        <div class="latest-games-wrapper">
            <div class="left-wrapper game-info-wrapper">
                <input type="hidden" value="<?php echo $newReleaseGameArray[0]['id'];?>" class="game-id">
                <img src="<?php echo $newReleaseGameArray[0]['profile_image'];?>" class="game-image">
                <div class="info-wrapper">
                    <div class="game-name"><?php echo $newReleaseGameArray[0]['title'];?></div>
                    <div class="splitter"></div>
                    <div class="genre"><?php echo get_genre_of_game($connect,$newReleaseGameArray[0]['id'])[0]['genre_type'];?></div>
                </div>
            </div>
            <div class="right-wrapper">
                <div class="up">
                    <div class="left game-info-wrapper">
                        <input type="hidden" value="<?php echo $newReleaseGameArray[1]['id'];?>" class="game-id">
                        <img src="<?php echo $newReleaseGameArray[1]['profile_image'];?>" class="game-image">
                        <div class="info-wrapper">
                            <div class="game-name"><?php echo $newReleaseGameArray[1]['title'];?></div>
                            <div class="splitter"></div>
                            <div class="genre"><?php echo get_genre_of_game($connect,$newReleaseGameArray[1]['id'])[0]['genre_type'];?></div>
                        </div>
                    </div>
                    <div class="right game-info-wrapper">
                        <input type="hidden" value="<?php echo $newReleaseGameArray[2]['id'];?>" class="game-id">
                        <img src="<?php echo $newReleaseGameArray[2]['profile_image'];?>" class="game-image">
                        <div class="info-wrapper">
                            <div class="game-name"><?php echo $newReleaseGameArray[2]['title'];?></div>
                            <div class="splitter"></div>
                            <div class="genre"><?php echo get_genre_of_game($connect,$newReleaseGameArray[2]['id'])[0]['genre_type'];?></div>
                        </div>
                    </div>
                </div>
                <div class="down">
                    <div class="left game-info-wrapper">
                        <input type="hidden" value="<?php echo $newReleaseGameArray[3]['id'];?>" class="game-id">
                        <img src="<?php echo $newReleaseGameArray[3]['profile_image'];?>" class="game-image">
                        <div class="info-wrapper">
                            <div class="game-name"><?php echo $newReleaseGameArray[3]['title'];?></div>
                            <div class="splitter"></div>
                            <div class="genre"><?php echo get_genre_of_game($connect,$newReleaseGameArray[3]['id'])[0]['genre_type'];?></div>
                        </div>
                    </div>
                    <div class="right game-info-wrapper">
                        <input type="hidden" value="<?php echo $newReleaseGameArray[4]['id'];?>" class="game-id">
                        <img src="<?php echo $newReleaseGameArray[4]['profile_image'];?>" class="game-image">
                        <div class="info-wrapper">
                            <div class="game-name"><?php echo $newReleaseGameArray[4]['title'];?></div>
                            <div class="splitter"></div>
                            <div class="genre"><?php echo get_genre_of_game($connect,$newReleaseGameArray[4]['id'])[0]['genre_type'];?></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <!-- Sale wrapper style.... -->
        <div class="sale-wrapper-title">Browse All Available Games</div>
        <div class="sale-wrapper">
            <?php
                for ( $count = 0 ; $count < $browseGameLimit ; $count++ ) {
                    $gameId = $browseGameArray[$count]['id'];
                    $isOnline = $browseGameArray[$count]['is_online_game'] === "yes" ? true : false;
                    $isPurchased = check_if_game_purchased($connect,$gameId,$customerId);
                    $posterImage = $browseGameArray[$count]['profile_image'];
                    $genre = "Fighting";
                    $gameTitle = $browseGameArray[$count]['title'];
                    $downloadSize = $browseGameArray[$count]['download_size'];
                    ?>
                    <div class="game-wrapper <?php echo ($count<5)?"mobile-version":"";?>">
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
                            <div style="display: <?php echo $isPurchased?'inline-block':'none';?>;" class="purchased-button"><i class="fa-solid fa-unlock"></i> Purchased</div>
                            <div style="display: <?php echo $isPurchased?'none':'inline-block';?>;" class="add-to-cart-button" game-id="<?php echo $gameId;?>"><i class="fa-solid fa-cart-shopping" aria-hidden="true"></i> Add to cart</div>
                            <div class="check-game-detail-button" game-id="<?php echo $gameId;?>"><i class="fa-solid fa-circle-info"></i> Check game info</div>
                        </div>
                    </div>
                    <?php
                }
            ?>
        </div>



        <div class="pagination-wrapper">
            <input type="hidden" value="<?php echo $currentPaginationNumber;?>" class="current-pagination-number">
            <?php
                if ( $totalPagination <= 9 ) {
                    for ( $count = 1 ; $count <= $totalPagination ; $count++ ) {
                        ?>
                            <div class="hoverable <?php echo ($count===$currentPaginationNumber)?"selected-number":"";?>">
                                <?php echo $count;?>
                            </div>
                        <?php
                    }
                } else {
            ?>
            <!-- This is the part of "pagination total number is greater than 9"............................................................................... -->
                <!-- first -->
                <div class="hoverable">
                    <?php
                    if ($currentPaginationNumber==1) {
                        echo 1;
                    } else {
                        echo "&lt;";
                    }
                    ?>
                </div>
                <!-- sec -->
                <div class="hoverable">
                    <?php
                        if ($currentPaginationNumber==1) {
                            echo $currentPaginationNumber+1;
                        } else if ( $currentPaginationNumber-1 <= 3 ) {
                            echo 1;
                        } else {
                            echo 1;
                        }
                    ?>
                </div>
                <!-- third -->
                <div class="hoverable">
                    <?php
                        if ($currentPaginationNumber==1) {
                            echo $currentPaginationNumber+2;
                        } else if ( $currentPaginationNumber - 1 <= 3 ) {
                            echo 2;
                        } else {
                            echo "...";
                        }
                    ?>
                </div>
                <!-- fourth -->
                <div class="hoverable">
                    <?php
                        if ($currentPaginationNumber==1) {
                            echo $currentPaginationNumber+3;
                        } else if ( $currentPaginationNumber - 1 <= 3 ) {
                            echo 3;
                        } else if ( $totalPagination - $currentPaginationNumber <= 3 ) {
                            echo $totalPagination-4;
                        }
                        else {
                            echo $currentPaginationNumber-1;
                        }
                    ?>
                </div>
                <!-- fifth -->
                <div class="hoverable">
                    <?php
                        if ($currentPaginationNumber==1) {
                            echo $currentPaginationNumber+4;
                        } else if ( $currentPaginationNumber - 1 <= 3 ) {
                            echo 4;
                        } else if ( $totalPagination - $currentPaginationNumber <= 3 ) {
                            echo $totalPagination-3;
                        } else {
                            echo $currentPaginationNumber;
                        }
                    ?>
                </div>
                <!-- sixth -->
                <div class="hoverable">
                    <?php
                        if ($currentPaginationNumber==1) {
                            echo $currentPaginationNumber+5;
                        } else if ( $currentPaginationNumber - 1 <= 3 ) {
                            echo 5;
                        } else if ( $totalPagination - $currentPaginationNumber <= 3 ) {
                            echo $totalPagination-2;
                        } else {
                            echo $currentPaginationNumber+1;
                        }
                    ?>
                </div>
                <!-- seventh -->
                <div class="hoverable">
                    <?php
                        if ( $totalPagination-$currentPaginationNumber <= 3 ) {
                            echo $totalPagination-1;
                        } else if ( $totalPagination - $currentPaginationNumber <= 3 ) {
                            echo $totalPagination-1;
                        } else {
                            echo "...";
                        }
                    ?>
                </div>
                <!-- eight -->
                <div class="hoverable">
                    <?php
                        if ( $totalPagination-$currentPaginationNumber <= 3 ) {
                            echo $totalPagination;
                        } else {
                            echo $totalPagination;
                        }
                    ?>
                </div>
                <!-- nine -->
                <div class="hoverable" style="display: <?php echo ($totalPagination==$currentPaginationNumber)?"none":"flex";?>;">
                    <?php
                        if ($totalPagination == $currentPaginationNumber ) {
                            echo $totalPagination;
                        } else {
                            echo "&gt;";
                        }
                    ?>
                </div>

                <?php
                }
            ?>
        </div>
    </div>


    <input type="hidden" value="<?php echo $isUserLoggedIn?"yes":"no";?>" class="is-user-logged-in">
    
    <div class="copyright-wrapper">
        Copyright &copy; 2023. All rights reserved by <span>Blacksky PC Game Store</span>
    </div>
    
    <script src="scripts/app-script.js"></script>
    <script src="scripts/navigation.js"></script>
    <!-- <script src="scripts/server-script.js"></script> -->
    <script src="scripts/add-to-cart.js"></script>
    <!-- <script src="scripts/cart-sync-with-server.js"></script> -->
</body>
</html>