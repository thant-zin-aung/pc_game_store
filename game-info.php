<?php
    session_start();
    include('admin_panel/db-connect.php');
    include('admin_panel/db-executions.php');
    $isUserLoggedIn = false;
    if ( isset($_SESSION['is-user-logged-in']) ) {
        $isUserLoggedIn = true;
        $loginUsername = $_SESSION['login-username'];
        $cartItemList = get_cart_item_list($connect,$loginUsername);
    }
    $gameId;
    if ( isset($_GET['game-id']) ) {
        $gameId = $_GET['game-id'];
    }
    $availableGenreList = get_available_genre_list($connect);
    $gameInfoArray = get_game_info($connect,$gameId);
    $gameGenreList = get_genre_of_game($connect,$gameId);
    $customerId = $isUserLoggedIn?(int)get_customer_info_by_username($connect,$loginUsername)['id']:0;
    $isPurchased = $isUserLoggedIn?is_admin($connect,$loginUsername)?true:check_if_game_purchased($connect,$gameId,$customerId):false;
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
    <link rel="stylesheet" href="splide-4/dist/css/splide.min.css">
    <link rel="stylesheet" href="styles/game-info-style.css">
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

    <div class="trailer-wrapper">
        <div class="iframe-wrapper">
            <iframe src="<?php echo $gameInfoArray['trailer_link'];?>" allowfullscreen></iframe>
            <!-- <i class="fa-solid fa-square-xmark close-button"></i> -->
            <button class="close-button"><i class="fa-regular fa-circle-xmark"></i> Close trailer</button>
        </div>
    </div>

    <section id="game-detail-wrapper">
        <div class="game-images-and-info-wrapper">
            <div class="game-images-wrapper">
                <img class="main-image" src="<?php echo $gameInfoArray['profile_image'];?>">
                <div class="info-wrapper">
                    <div class="game-title"><?php echo $gameInfoArray['title'];?></div>
                    <div class="genre">Genre: 
                        <span>
                        <?php
                            foreach($gameGenreList as $genre) {
                                echo $genre['genre_type'].", ";
                            }
                        ?>
                        </span>
                    </div>
                    <div class="download-size">Download size: <span><?php echo $gameInfoArray['download_size'];?> GB</span></div>
                    <div class="button-wrapper">
                        <div style="display: <?php echo $isPurchased?'inline-block':'none';?>;" class="button purchased-button"><i class="fa-solid fa-unlock"></i> Purchased</div>
                        <div style="display: <?php echo $isPurchased?'none':'inline-block';?>;" class="button add-to-cart-button" game-id="<?php echo $gameId;?>"><i class="fa-solid fa-cart-shopping" aria-hidden="true"></i> Add to cart</div>
                        <div class="button watch-trailer-button"><i class="fa-regular fa-circle-play"></i> Watch trailer</div>
                    </div>
                </div>
    
                <div class="thumbnail-wrapper">
                    <img class="current-image" src="<?php echo $gameInfoArray['profile_image'];?>">
                    <img src="<?php echo $gameInfoArray['additional_image_1'];?>">
                    <img src="<?php echo $gameInfoArray['additional_image_2'];?>">
                    <img src="<?php echo $gameInfoArray['additional_image_3'];?>">
                    <img src="<?php echo $gameInfoArray['additional_image_4'];?>">
                </div>
            </div>

            <div class="game-info-wrapper">
                <div class="title"><?php echo $gameInfoArray['title'];?></div>
                <div class="genre-wrapper">
                    <?php
                        foreach($gameGenreList as $genre) {
                            ?>
                            <div class="genre"><?php echo $genre['genre_type'];?></div>        
                            <?php
                        }
                    ?>
                </div>
                <div class="info">
                    <div class="type">Developer</div>
                    <div class="value"><?php echo $gameInfoArray['developer'];?></div>
                </div>
                <div class="info">
                    <div class="type">Publisher</div>
                    <div class="value"><?php echo $gameInfoArray['publisher'];?></div>
                </div>
                <div class="info">
                    <div class="type">Download size</div>
                    <div class="value"><?php echo $gameInfoArray['download_size'];?> GB</div>
                </div>
                <div class="download-link-title">Download links <span><i class="fa-solid fa-link"></i></span></div>
                <div class="locked-wrapper" style="display: <?php echo $isPurchased?'none':'flex';?>;">
                    <div><i class="fa-solid fa-lock"></i> Locked</div>
                    <div class="hint">All the links are appear as soon as you purchase on this game. Please purchase the game to unlock download links.</div>
                </div>
                <div class="link-wrapper" style="display: <?php echo $isPurchased?'flex':'none';?>;">
                    <?php
                        $gameDownloadLinks2 = $gameInfoArray['download_link_2'];
                        $gameDownloadLinks2Array = explode("\n",$gameDownloadLinks2);
                        for ( $count = 0 ; $count < count($gameDownloadLinks2Array) ; $count++ ) {
                            ?>
                                <a class="link" href="<?php echo $gameDownloadLinks2Array[$count];?>" target="_blank">Part <?php echo $count+1;?></a>
                            <?php
                        }
                    ?>
                    <!-- <a class="link" href="" target="_blank">Part 1</a>
                    <a class="link" href="" target="_blank">Part 2</a>
                    <a class="link" href="" target="_blank">Part 3</a>
                    <a class="link" href="" target="_blank">Part 4</a>
                    <a class="link" href="" target="_blank">Part 5</a>
                    <a class="link" href="" target="_blank">Part 6</a>
                    <a class="link" href="" target="_blank">Part 7</a>
                    <a class="link" href="" target="_blank">Part 8</a>
                    <a class="link" href="" target="_blank">Part 9</a>
                    <a class="link" href="" target="_blank">Part 10</a>
                    <a class="link" href="" target="_blank">Part 11</a> -->
                </div>
            </div>
        </div>

        <div class="system-requirement-title">System requirements <span><i class="fa-regular fa-rectangle-list"></i></span></div>
        <div class="system-requirement-wrapper">
            <div class="spec-wrapper mininum-wrapper">
                <div class="title">Minimum</div>
                <div class="sys-info">
                    <div class="type">OS</div>
                    <div class="value"><?php echo $gameInfoArray['spec_min_os'];?></div>
                </div>
                <div class="sys-info">
                    <div class="type">Processor</div>
                    <div class="value"><?php echo $gameInfoArray['spec_min_processor'];?></div>
                </div>
                <div class="sys-info">
                    <div class="type">Memory</div>
                    <div class="value"><?php echo $gameInfoArray['spec_min_memory'];?></div>
                </div>
                <div class="sys-info">
                    <div class="type">Graphics</div>
                    <div class="value"><?php echo $gameInfoArray['spec_min_graphics'];?></div>
                </div>
                <div class="sys-info">
                    <div class="type">Storage</div>
                    <div class="value"><?php echo $gameInfoArray['spec_min_storage'];?></div>
                </div>
                <div class="sys-info">
                    <div class="type">DirectX</div>
                    <div class="value"><?php echo $gameInfoArray['spec_min_directx'];?></div>
                </div>
            </div>

            <div class="spec-wrapper recommended-wrapper">
                <div class="title">Recommended</div>
                <div class="sys-info">
                    <div class="type">OS</div>
                    <div class="value"><?php echo $gameInfoArray['spec_max_os'];?></div>
                </div>
                <div class="sys-info">
                    <div class="type">Processor</div>
                    <div class="value"><?php echo $gameInfoArray['spec_max_processor'];?></div>
                </div>
                <div class="sys-info">
                    <div class="type">Memory</div>
                    <div class="value"><?php echo $gameInfoArray['spec_max_memory'];?></div>
                </div>
                <div class="sys-info">
                    <div class="type">Graphics</div>
                    <div class="value"><?php echo $gameInfoArray['spec_max_graphics'];?></div>
                </div>
                <div class="sys-info">
                    <div class="type">Storage</div>
                    <div class="value"><?php echo $gameInfoArray['spec_max_storage'];?></div>
                </div>
                <div class="sys-info">
                    <div class="type">DirectX</div>
                    <div class="value"><?php echo $gameInfoArray['spec_max_directx'];?></div>
                </div>
            </div>
        </div>


        <div class="splitter"></div>

        <div class="related-games-title">Related games</div>
        <section class="splide related-games-wrapper" aria-label="Splide Basic HTML Example">
            <div class="splide__track">
                  <ul class="splide__list">
                        <?php
                            $relatedGameList = get_game_list_by_genre_list($connect,...$gameGenreList);
                            $listLimit = count($relatedGameList)>20 ? 20 : count($relatedGameList);
                            for ( $count = 0 ; $count < $listLimit ; $count++ ) {
                                $relatedGameId = $relatedGameList[$count]['id'];
                                if ( $relatedGameId == $gameId ) continue;
                                $gameTitle = $relatedGameList[$count]['title'];
                                $isOnline = $relatedGameList[$count]['is_online_game']=="yes"?true:false;
                                $isPurchased = check_if_game_purchased($connect,$relatedGameId,$customerId);
                                $downloadSize = $relatedGameList[$count]['download_size'];
                                $posterImage = $relatedGameList[$count]['profile_image'];
                                $relatedGameGenreList = get_genre_of_game($connect,$relatedGameId);
                                ?>
                                <li class="splide__slide game-wrapper">
                                    <div class="game-image-wrapper">
                                        <div class="status-wrapper">
                                            <div class="status online-status" style="display: <?php echo $isOnline?'block':'none';?>;">Online</div>
                                            <div class="status purchased-status" style="display: <?php echo $isPurchased?'block':'none';?>;">Purchased</div>
                                        </div>
                                        <img src="<?php echo $posterImage;?>">
                                        <div class="genre-wrapper">
                                            <?php
                                                foreach($relatedGameGenreList as $genre) {
                                                    ?>
                                                    <div class="genre"><?php echo $genre['genre_type'];?></div>
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
                                        <div class="check-game-detail-button" game-id="<?php echo $relatedGameId;?>"><i class="fa-solid fa-circle-info"></i> Check game info</div>
                                    </div>
                                </li>
                                <?php
                            }
                        ?>
                  </ul>
            </div>
        </section>

        
    </section>

    <input type="hidden" value="<?php echo $isUserLoggedIn?"yes":"no";?>" class="is-user-logged-in">

    <div class="copyright-wrapper">
        Copyright &copy; 2023. All rights reserved by <span>Blacksky PC Game Store</span>.
    </div>


    <script src="splide-4/dist/js/splide.min.js"></script>
    <script src="scripts/app-script.js"></script>
    <script src="scripts/game-info-animation.js"></script>
    <script src="scripts/game-info.js"></script>
    <script src="scripts/navigation.js"></script>
    <script src="scripts/add-to-cart.js"></script>
</body>
</html>