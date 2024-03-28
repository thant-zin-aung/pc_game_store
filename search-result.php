<?php
    include('admin_panel/db-connect.php');
    include('admin_panel/db-executions.php');
    session_start();
    $isUserLoggedIn = false;
    if ( isset($_SESSION['is-user-logged-in']) ) {
        $isUserLoggedIn = true;
        $loginUsername = $_SESSION['login-username'];
        $cartItemList = get_cart_item_list($connect,$loginUsername);
    }
    // If the customer search by genre...........................................
    $urlVariable="";
    $browseGameLimit = 30;
    $customerId = $isUserLoggedIn?(int)get_customer_info_by_username($connect,$loginUsername)['id']:0;
    if ( isset($_GET['genre-id']) ) {
        $genreId = $_GET['genre-id'];
        $urlVariable = "genre-id=$genreId";
        $genreType = get_genre_type_by_genre_id($connect,$genreId);
        // $gameList = get_game_list_by_genre_id($connect,$genreId);
        $totalGameQuery = "SELECT count(id) as total_game FROM game_list as gl INNER JOIN game_genre_list as ggl ON gl.id=ggl.game_id WHERE ggl.genre_id=$genreId";
        // Order is important....
        // Page pagination codes.....
        // $browseGameLimit = 20;
        $totalGameQueryResult = mysqli_query($connect,$totalGameQuery);
        $totalGame = mysqli_fetch_array($totalGameQueryResult)['total_game'];
        $totalPagination = ceil($totalGame/$browseGameLimit);
        $currentPaginationNumber = 1;
        if ( isset($_GET['page-number']) ) {
            $currentPaginationNumber = $_GET['page-number'] > $totalPagination ? $totalPagination : $_GET['page-number'];
        }
        $searchGameStartingPoint = $browseGameLimit*($currentPaginationNumber-1);
        if ( ($totalGame - $searchGameStartingPoint) < $browseGameLimit ) {
            $browseGameLimit = $totalGame - $searchGameStartingPoint+1;
        }
        $gameList = get_game_list_by_genre_id_using_range($connect,$genreId,$searchGameStartingPoint,$browseGameLimit);
        // Page pagination codes.....
        // Order is important....
    }
    // If the customer search by clicking online games tab.........................................
    else if ( isset($_GET['game-type']) ) {
        // $gameList = get_online_game_list($connect);
        $urlVariable = "game-type=online";
        $totalGameQuery = "SELECT count(id) as total_game FROM game_list WHERE is_online_game='yes'";
        // Order is important....
        // Page pagination codes.....
        // $browseGameLimit = 3;
        $totalGameQueryResult = mysqli_query($connect,$totalGameQuery);
        $totalGame = mysqli_fetch_array($totalGameQueryResult)['total_game'];
        $totalPagination = ceil($totalGame/$browseGameLimit);
        $currentPaginationNumber = 1;
        if ( isset($_GET['page-number']) ) {
            $currentPaginationNumber = $_GET['page-number'] > $totalPagination ? $totalPagination : $_GET['page-number'];
        }
        $searchGameStartingPoint = $browseGameLimit*($currentPaginationNumber-1);
        if ( ($totalGame - $searchGameStartingPoint) < $browseGameLimit ) {
            $browseGameLimit = $totalGame - $searchGameStartingPoint+1;
        }
        $gameList = get_online_game_list_using_range($connect,$searchGameStartingPoint,$browseGameLimit);
        // Page pagination codes.....
        // Order is important....
    }
    // If the customer search by search box typing game title keyword.........................................
    else if ( isset($_GET['game-title-keyword']) ) {
        $gameTitleKeyword = $_GET['game-title-keyword'];
        $urlVariable = "game-title-keyword=".$gameTitleKeyword;
        $totalGameQuery = "SELECT count(id) as total_game FROM game_list WHERE title LIKE '%%$gameTitleKeyword%%'";
        // Order is important....
        // Page pagination codes.....
        // $browseGameLimit = 3;
        $totalGameQueryResult = mysqli_query($connect,$totalGameQuery);
        $totalGame = mysqli_fetch_array($totalGameQueryResult)['total_game'];
        $totalPagination = ceil($totalGame/$browseGameLimit);
        $currentPaginationNumber = 1;
        if ( isset($_GET['page-number']) ) {
            $currentPaginationNumber = $_GET['page-number'] > $totalPagination ? $totalPagination : $_GET['page-number'];
        }
        $searchGameStartingPoint = $browseGameLimit*($currentPaginationNumber-1);
        if ( ($totalGame - $searchGameStartingPoint) < $browseGameLimit ) {
            $browseGameLimit = $totalGame - $searchGameStartingPoint+1;
        }
        $gameList = get_game_list_by_game_title_keyword_using_range($connect,$gameTitleKeyword,$searchGameStartingPoint,$browseGameLimit);
        // Page pagination codes.....
        // Order is important....
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

    <div class="main-wrapper">
        <!-- Sale wrapper style.... -->
        <div class="sale-wrapper-title">Search results: "
            <?php
                if ( isset($_GET['genre-id']) ) {
                    echo "genre - $genreType";
                } else if ( isset($_GET['game-type']) ) {
                    echo "Online game list";
                } else if ( isset($_GET['game-title-keyword'])) {
                    echo $urlVariable;
                }
            ?>
            "</div>
        <div class="sale-wrapper">
            <?php
                foreach($gameList as $game) {
                    $gameId = $game['id'];
                    $gameTitle = $game['title'];
                    $isOnline = $game['is_online_game']=="yes"?true:false;
                    $isPurchased = check_if_game_purchased($connect,$gameId,$customerId);
                    $downloadSize = $game['download_size'];
                    $posterImage = $game['profile_image'];
                    $gameGenreList = get_genre_of_game($connect,$gameId);
                    $limitedGenreAmount = count($gameGenreList)>4 ? 4 : count($gameGenreList);
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
                                    for ( $genreCount = 0 ; $genreCount < $limitedGenreAmount ; $genreCount++ ) {
                                        $genreType = $gameGenreList[$genreCount]['genre_type'];
                                        ?>
                                            <div class="genre"><?php echo $genreType;?></div>
                                        <?php
                                    }
                                ?>
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
            <input type="hidden" value="<?php echo $urlVariable;?>" class="url-variable">
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
    <script src="scripts/search-result.js"></script>
    <script src="scripts/navigation.js"></script>
    <!-- <script src="scripts/app-script.js"></script> -->
    <script src="scripts/add-to-cart.js"></script>
</body>
</html>