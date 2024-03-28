<?php
    session_start();
    include('db-connect.php');
    include('db-executions.php');
    if ( isset($_SESSION['is_admin_logged_in']) ) {
        $adminUsername = $_SESSION['admin_username'];
    } else {
        header("location: login.php");
    }
    $totalNewOrder = get_total_new_orders_count($connect);

    if ( isset($_POST['save-button']) ) {
        $gameId = (int)$_GET['game-id'];
        $genres = $_POST['genre'];
        if ( count($genres) === 0 ) {
            echo "<script>alert('Warning!!! Please select at least one genre...');</script>";
            echo "<script>window.location='game-info.php?game-id=$gameId';</script>";
            exit();
        }
        $gameTitle = $_POST['game-title'];
        $gameDeveloper = $_POST['game-developer'];
        $gamePublisher = $_POST['game-publisher'];
        $downloadSize = $_POST['download-size'];
        $isOnlineGame = $_POST['is-online-game'];
        $imageLinks = explode("\n",$_POST['image-links']);
        $posterImage = $imageLinks[0];
        $additionalImage1 = $imageLinks[1];
        $additionalImage2 = $imageLinks[2];
        $additionalImage3 = $imageLinks[3];
        $additionalImage4 = $imageLinks[4];
        $trailerLink = $_POST['trailer-link'];
        $specMinOs = $_POST['spec-min-os'];
        $specMinProcessor = $_POST['spec-min-processor'];
        $specMinMemory = $_POST['spec-min-memory'];
        $specMinGraphics = $_POST['spec-min-graphics'];
        $specMinStorage = $_POST['spec-min-storage'];
        $specMinDirectX = $_POST['spec-min-directx'];
        $specMaxOs = $_POST['spec-max-os'];
        $specMaxProcessor = $_POST['spec-max-processor'];
        $specMaxMemory = $_POST['spec-max-memory'];
        $specMaxGraphics = $_POST['spec-max-graphics'];
        $specMaxStorage = $_POST['spec-max-storage'];
        $specMaxDirectX = $_POST['spec-max-directx'];
        $downloadLink1 = $_POST['download-links-1'];
        $downloadLink2 = $_POST['download-links-2'];

        $updateGameQuery = "UPDATE game_list 
                    SET title='$gameTitle', developer='$gameDeveloper', publisher='$gamePublisher', download_size=$downloadSize, is_online_game='$isOnlineGame', profile_image='$posterImage', additional_image_1='$additionalImage1', 
                    additional_image_2='$additionalImage2', additional_image_3='$additionalImage3', additional_image_4='$additionalImage4', trailer_link='$trailerLink', download_link_1='$downloadLink1', download_link_2='$downloadLink2', 
                    spec_min_os='$specMinOs', spec_min_processor='$specMinProcessor', spec_min_memory='$specMinMemory', spec_min_graphics='$specMinGraphics', spec_min_storage='$specMinStorage', spec_min_directx='$specMinDirectX', 
                    spec_max_os='$specMaxOs', spec_max_processor='$specMaxProcessor', spec_max_memory='$specMaxMemory', spec_max_graphics='$specMaxGraphics', spec_max_storage='$specMaxStorage', spec_max_directx='$specMaxDirectX' 
                    WHERE id=$gameId";
        $updateGameQueryResult = mysqli_query($connect,$updateGameQuery);
        
        if ( $updateGameQueryResult ) {
            $deleteGameGenreQuery = "DELETE FROM game_genre_list WHERE game_id=$gameId";
            $deleteGameGenreQueryResult = mysqli_query($connect,$deleteGameGenreQuery);
            if ( $deleteGameGenreQueryResult ) {
                $addGameCondition = true;
                for ( $count = 0 ; $count < count($genres) ; $count++ ) {
                    $addGameGenreQuery = "INSERT INTO game_genre_list(game_id,genre_id) VALUES($gameId,$genres[$count])";
                    $addGameGenreQueryResult = mysqli_query($connect,$addGameGenreQuery);
                    if ( !$addGameGenreQueryResult ) {
                        $addGameCondition = false;
                        break;
                    }
                }
                if ( $addGameCondition ) {
                    echo "<script>alert('Game data updated successfully...');</script>";        
                } else {
                    echo "<script>alert('Warning!!! Failed to update game genre data...');</script>";
                }
            }
        } else {
            echo "<script>alert('Warning!!! Failed to update the whole game data...');</script>";
        }


    }
    // these codes work if the game detail was click from game list page...
    if ( isset($_GET['game-id']) ) {
        $gameId = (int)$_GET['game-id'];
        // for displaying genre list...
        $genreQuery = "SELECT * FROM genre_list";
        $genreQueryResult = mysqli_query($connect,$genreQuery);
        $totalGenre = mysqli_num_rows($genreQueryResult);
        // for displaying genre list...

        // for displaying selected genre list...
        $selectedGenreQuery = "SELECT * FROM game_genre_list WHERE game_id=$gameId";
        $selectedGenreQueryResult = mysqli_query($connect,$selectedGenreQuery);
        $totalSelectedGenre = mysqli_num_rows($selectedGenreQueryResult);
        $selectedGenreArray;
        for ( $gCount = 0 ; $gCount < $totalSelectedGenre; $gCount++ ) {
            $selectedGenreData = mysqli_fetch_array($selectedGenreQueryResult);
            $selectedGenreId = $selectedGenreData['genre_id'];
            $selectedGenreArray[] = $selectedGenreId;
        }
        // for displaying selected genre list...

        // for displaying game information...
        $gameDataQuery = "SELECT * FROM game_list WHERE id=$gameId";
        $gameDataQueryResult = mysqli_query($connect,$gameDataQuery);
        $gameData = mysqli_fetch_array($gameDataQueryResult);
        $gameTitle = $gameData['title'];
        $gameDeveloper = $gameData['developer'];
        $gamePublisher = $gameData['publisher'];
        $gameDownloadSize = $gameData['download_size'];
        $isOnlineGame = $gameData['is_online_game'];
        $posterImage = $gameData['profile_image'];
        $additionalImage1 = $gameData['additional_image_1'];
        $additionalImage2 = $gameData['additional_image_2'];
        $additionalImage3 = $gameData['additional_image_3'];
        $additionalImage4 = $gameData['additional_image_4'];
        $trailerLink = $gameData['trailer_link'];
        $downloadLink1 = $gameData['download_link_1'];
        $downloadLink2 = $gameData['download_link_2'];
        $specMinOs = $gameData['spec_min_os'];
        $specMinProcessor = $gameData['spec_min_processor'];
        $specMinMemory = $gameData['spec_min_memory'];
        $specMinGraphics = $gameData['spec_min_graphics'];
        $specMinStorage = $gameData['spec_min_storage'];
        $specMinDirectX = $gameData['spec_min_directx'];
        $specMaxOs = $gameData['spec_max_os'];
        $specMaxProcessor = $gameData['spec_max_processor'];
        $specMaxMemory = $gameData['spec_max_memory'];
        $specMaxGraphics = $gameData['spec_max_graphics'];
        $specMaxStorage = $gameData['spec_max_storage'];
        $specMaxDirectX = $gameData['spec_max_directx'];
        // for displaying game information...
    }

    $purchasedCustomerListQuery = "SELECT c.id,c.customer_username FROM customer_list as c INNER JOIN order_list as ol ON ol.customer_id=c.id INNER JOIN ordered_games_list as ogl ON ogl.order_id=ol.id WHERE ogl.game_id=$gameId AND ol.order_status='approved';";
    $purchasedCustomerListQueryResult = mysqli_query($connect,$purchasedCustomerListQuery);
    $totalPurchasedCustomer = mysqli_num_rows($purchasedCustomerListQueryResult);
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
    <!-- <link rel="stylesheet" href="styles/game-list-style.css"> -->
    <link rel="stylesheet" href="styles/game-info.css">
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
            <div class="customer-tab tab"><span><i class="fa-solid fa-users"></i> Customers</span></div>
            <div class="game-list-tab tab active-tab"><span><i class="fa-solid fa-list"></i> Game list</span></div>
            <div class="admin-control-tab tab"><span><i class="fa-solid fa-user-tie"></i> Admin Control</span></div>
            <div class="image-uploader-tab tab"><span><i class="fa-regular fa-file-image"></i> Image Uploader</span></div>
            <div class="log-out-tab tab"><span><i class="fa-solid fa-arrow-right-from-bracket"></i> Log Out</span></div>
        </div>
    </nav>

    <div class="main-wrapper">
        <form action="game-info.php?game-id=<?php echo $gameId;?>" method="POST">
            <div class="edit-save-button-wrapper">
                <button type="submit" class="button save-button" name="save-button">SAVE</button>
                <div class="button edit-button">EDIT</div>
                <div class="button close-button"><i class="fa-regular fa-circle-xmark"></i> &nbsp; CLOSE</div>
            </div>
            <div class="title"><?php echo $gameTitle;?></div>
            <div class="image-and-game-detail-wrapper">
                <div class="image-wrapper">
                    <img src="<?php echo $posterImage;?>" class="poster-image">
                    <div class="additional-image-wrapper">
                        <img src="<?php echo $additionalImage1;?>" class="additional-image">
                        <img src="<?php echo $additionalImage2;?>" class="additional-image">
                        <img src="<?php echo $additionalImage3;?>" class="additional-image">
                        <img src="<?php echo $additionalImage4;?>" class="additional-image">
                    </div>
                </div>

                <div class="game-detail-wrapper">
                    <div class="game-detail-title">
                        <div class="title">Game Details</div>
                        <div class="is-online-wrapper">
                            <div class="is-online-title">Online Game: </div>
                            <div class="radio-button-wrapper">
                                <input class="disabled-element" type="radio" name="is-online-game" value="no" id="is-not-online-game" <?php echo $isOnlineGame==="no"?"checked":"";?> disabled>
                                <label for="is-not-online-game">no</label>
                                <input class="disabled-element" type="radio" name="is-online-game" value="yes" id="is-online-game" <?php echo $isOnlineGame==="yes"?"checked":"";?> disabled>
                                <label for="is-online-game">yes</label>
                            </div>
                        </div>
                    </div>
                    <div class="detail-wrapper">
                        <div class="type">Game Title</div>
                        <div class="value">
                            <input class="disabled-element game-title-input" type="text" name="game-title" value="<?php echo $gameTitle;?>" required disabled>
                            <!-- <button><i class="fa-regular fa-pen-to-square"></i> Edit</button> -->
                        </div>
                    </div>
                    <div class="detail-wrapper">
                        <div class="type">Developer</div>
                        <div class="value">
                            <input class="disabled-element" type="text" name="game-developer" value="<?php echo $gameDeveloper;?>" required disabled>
                            <!-- <button><i class="fa-regular fa-pen-to-square"></i> Edit</button> -->
                        </div>
                    </div>
                    <div class="detail-wrapper">
                        <div class="type">Publisher</div>
                        <div class="value">
                            <input class="disabled-element" type="text" name="game-publisher" value="<?php echo $gamePublisher;?>" required disabled>
                            <!-- <button><i class="fa-regular fa-pen-to-square"></i> Edit</button> -->
                        </div>
                    </div>
                    <div class="detail-wrapper">
                        <div class="type">Download Size (GB)</div>
                        <div class="value">
                            <input class="disabled-element" type="number" name="download-size" value="<?php echo $gameDownloadSize;?>" required disabled>
                            <!-- <button><i class="fa-regular fa-pen-to-square"></i> Edit</button> -->
                        </div>
                    </div>
                    <div class="detail-wrapper">
                            <div class="type">Youtube Trailer Link</div>
                            <div class="value">
                                <input class="disabled-element" type="text" name="trailer-link" value="<?php echo $trailerLink;?>" name="trailer-link" required disabled>
                                <!-- <button><i class="fa-regular fa-pen-to-square"></i> Edit</button> -->
                            </div>
                        </div>
                    <div class="link-title">
                        <div class="title">Image Links</div>
                        <div class="image-uploader-wrapper">
                            <label for="image-files-input" class="upload-button"><i class="fa-solid fa-arrow-up-from-bracket"></i> &nbsp; Upload Images</label>
                            <input type="file" class="image-files-input" id="image-files-input" accept="image/*" multiple>
                        </div>
                        <div class="wait-label"><i class="fa-solid fa-hourglass-start"></i> &nbsp; please wait while uploading...</div>
                        <!-- <div class="status success">Successfully uploaded...</div>
                        <div class="status fail">failed to upload...</div> -->
                    </div>
                    <textarea name="image-links" placeholder="First link refers to game poster image link...." class="link-box disabled-element" spellcheck="false" disabled><?php echo "$posterImage\n$additionalImage1\n$additionalImage2\n$additionalImage3\n$additionalImage4";?></textarea>
                    <!-- <button class="edit-button"><i class="fa-regular fa-pen-to-square"></i> Edit New Links</button> -->
                    <div class="genre-title">Genre</div>
                    <div class="genre-wrapper">
                        <?php
                            for ( $count = 0 ; $count < $totalGenre ; $count++ ) {
                                $genreData = mysqli_fetch_array($genreQueryResult);
                                $genreId = $genreData['id'];
                                $genreType = $genreData['genre_type'];
                                $isGenreSelected = in_array($genreId,$selectedGenreArray);
                            ?>
                                <div class="genre">
                                    <input class="disabled-element" type="checkbox" name="genre[]" value="<?php echo $genreId;?>" id="genre-id-<?php echo $genreId;?>" <?php echo $isGenreSelected?"checked":""?> disabled>
                                    <label for="genre-id-<?php echo $genreId;?>"><?php echo $genreType;?></label>
                                </div>
                            <?php
                            }
                        ?>
                    </div>
                </div>
            </div>

            <div class="spec-title">Specifications</div>
            <div class="system-requirement-wrapper">
                <div class="spec-wrapper mininum-wrapper">
                    <div class="title">Minimum</div>
                    <div class="sys-info">
                        <div class="type">OS</div>
                        <div class="value"><input class="disabled-element" type="text" spellcheck="false" name="spec-min-os" value="<?php echo $specMinOs;?>" disabled></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">Processor</div>
                        <div class="value"><input class="disabled-element" type="text" spellcheck="false" name="spec-min-processor" value="<?php echo $specMinProcessor;?>" disabled></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">Memory</div>
                        <div class="value"><input class="disabled-element" type="text" spellcheck="false" name="spec-min-memory" value="<?php echo $specMinMemory;?>" disabled></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">Graphics</div>
                        <div class="value"><input class="disabled-element" type="text" spellcheck="false" name="spec-min-graphics" value="<?php echo $specMinGraphics;?>" disabled></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">Storage</div>
                        <div class="value"><input class="disabled-element" type="text" spellcheck="false" name="spec-min-storage" value="<?php echo $specMinStorage;?>" disabled></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">DirectX</div>
                        <div class="value"><input class="disabled-element" type="text" spellcheck="false" name="spec-min-directx" value="<?php echo $specMinDirectX;?>" disabled></div>
                    </div>
                </div>

                <div class="spec-wrapper recommended-wrapper">
                    <div class="title">Recommended</div>
                    <div class="sys-info">
                        <div class="type">OS</div>
                        <div class="value"><input class="disabled-element" type="text" spellcheck="false" name="spec-max-os" value="<?php echo $specMaxOs;?>" disabled></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">Processor</div>
                        <div class="value"><input class="disabled-element" type="text" spellcheck="false" name="spec-max-processor" value="<?php echo $specMaxProcessor;?>" disabled></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">Memory</div>
                        <div class="value"><input class="disabled-element" type="text" spellcheck="false" name="spec-max-memory" value="<?php echo $specMaxMemory;?>" disabled></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">Graphics</div>
                        <div class="value"><input class="disabled-element" type="text" spellcheck="false" name="spec-max-graphics" value="<?php echo $specMaxGraphics;?>" disabled></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">Storage</div>
                        <div class="value"><input class="disabled-element" type="text" spellcheck="false" name="spec-max-storage" value="<?php echo $specMaxStorage;?>" disabled></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">DirectX</div>
                        <div class="value"><input class="disabled-element" type="text" spellcheck="false" name="spec-max-directx" value="<?php echo $specMaxDirectX;?>" disabled></div>
                    </div>
                </div>
            </div>

            <div class="download-link-title">Downloads Links</div>
            <div class="download-link-wrapper">
                <div class="left-wrapper">
                    <div class="link-title">Pixel Drain</div>
                    <br>
                    <textarea name="download-links-1" class="download-link-box pixel-drain-box disabled-element" placeholder="Please enter only pixeldrain file ids..." spellcheck="false" disabled><?php echo $downloadLink1;?></textarea>
                </div>
                <div class="right-wrapper">
                    <div class="link-title">Mega Up</div>
                    <br>
                    <textarea name="download-links-2" class="download-link-box mega-up-box disabled-element" placeholder="Please enter only megaup download links..." spellcheck="false" disabled><?php echo $downloadLink2;?></textarea>
                </div>
            </div>
        </form>



        <!-- Purchased customer list....................... -->
        <div class="customer-title-wrapper">
            <div class="customer-title">Purchased Customer List <span>(<?php echo $totalPurchasedCustomer;?>)</span></div>
            <div class="search-box"><i class="fa-solid fa-magnifying-glass"></i> <input type="text" placeholder="Search customer username..." class="customer-search-box" spellcheck="false"></div>
        </div>
        <table class="customer-table" cellspacing="0">
            <tr>
                <th># Customer ID</th>
                <th>Customer Username</th>
                <th>Total Order</th>
                <th>Action</th>
            </tr>
            <?php
            for ( $count=0 ; $count<$totalPurchasedCustomer ; $count++ ) {
                $purchasedCustomerData = mysqli_fetch_array($purchasedCustomerListQueryResult);
                $customerId = (int)$purchasedCustomerData['id'];
                $customerUsername=$purchasedCustomerData['customer_username'];
                $purchasedCustomerTotalOrder = get_total_order_by_customer_id($connect,$customerId);
                ?>
                <tr>
                    <td><?php echo $customerId;?></td>
                    <td class="customer-username"><?php echo $customerUsername;?></td>
                    <td><?php echo $purchasedCustomerTotalOrder;?> Orders</td>
                    <td>
                        <button class="more-detail-button" customer-id="<?php echo $customerId;?>">More Detail >>></button>
                    </td>
                </tr>
                <?php
            }
            ?>
            <!-- <tr>
                <td>1</td>
                <td class="customer-username">blacksky00797</td>
                <td>4 Orders</td>
                <td>
                    <button class="more-detail-button">More Detail >>></button>
                </td>
            </tr> -->
        </table>
        

        <div class="copyright-wrapper">
            Copyright &copy; 2023. All rights reserved by &nbsp;<span>Blacksky PC Game Store</span>.
        </div>
    </div>
    
    <script src="scripts/app-script.js"></script>
    <script src="scripts/game-info-script.js"></script>
    <script src="scripts/tab-navigator.js"></script>
    
</body>
</html>