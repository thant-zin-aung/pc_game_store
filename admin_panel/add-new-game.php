<?php
    session_start();
    include('db-connect.php');
    include('db-executions.php');
    if ( isset($_SESSION['is_admin_logged_in']) ) {
        $adminUsername = $_SESSION['admin_username'];
    } else {
        header("location: login.php");
    }
    // for displaying genre list...
    $genreQuery = "SELECT * FROM genre_list";
    $genreQueryResult = mysqli_query($connect,$genreQuery);
    $totalGenre = mysqli_num_rows($genreQueryResult);
    // for displaying genre list...

    $totalNewOrder = get_total_new_orders_count($connect);
    if ( isset($_POST['save-button']) ) {
        $genres = $_POST['genre'];
        if ( count($genres) === 0 ) {
            echo "<script>alert('Warning!!! Please select at least one genre...');</script>";
            echo "<script>window.location='add-new-game.php';</script>";
            exit();
        }
        $addGameCondition = true;
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
        
        // Order is important...
        $addGameQuery = "INSERT INTO game_list(
            title,developer,publisher,download_size,is_online_game,profile_image,additional_image_1,additional_image_2,additional_image_3,additional_image_4,trailer_link,download_link_1,download_link_2,
            spec_min_os,spec_min_processor,spec_min_memory,spec_min_graphics,spec_min_storage,spec_min_directx,spec_max_os,spec_max_processor,spec_max_memory,spec_max_graphics,spec_max_storage,spec_max_directx
        ) VALUES(
            '$gameTitle','$gameDeveloper','$gamePublisher',$downloadSize,'$isOnlineGame','$posterImage','$additionalImage1','$additionalImage2','$additionalImage3','$additionalImage4',
            '$trailerLink','$downloadLink1','$downloadLink2','$specMinOs','$specMinProcessor','$specMinMemory','$specMinGraphics','$specMinStorage','$specMinDirectX','$specMaxOs','$specMaxProcessor',
            '$specMaxMemory','$specMaxGraphics','$specMaxStorage','$specMaxDirectX'
        )";

        $addGameQueryResult = mysqli_query($connect,$addGameQuery);

        $lastGameId = get_last_game_id($connect);
        for ( $count = 0 ; $count < count($genres) ; $count++ ) {
            $addGameGenreQuery = "INSERT INTO game_genre_list(game_id,genre_id) VALUES($lastGameId,$genres[$count])";
            $addGameGenreQueryResult = mysqli_query($connect,$addGameGenreQuery);
            if ( !$addGameGenreQuery ) {
                $addGameCondition = false;
                break;
            }
        }
        if ( $addGameQueryResult && $addGameCondition ) {
            echo "<script>alert('New game added successfully...');</script>";
            echo "<script>window.location='game-list.php';</script>";
        } else {
            echo "<script>alert('Warning!!! Failed to add new game...');</script>";
        }
        // Order is important...
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
    <!-- <link rel="stylesheet" href="styles/game-list-style.css"> -->
    <link rel="stylesheet" href="styles/game-info.css">
    <link rel="stylesheet" href="styles/add-new-game-style.css">
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
        <form action="add-new-game.php" method="POST">
            <div class="edit-save-button-wrapper">
                <!-- <div class="button edit-button">EDIT</div> -->
                <button type="submit" class="button save-button" name="save-button">SAVE</button>
            </div>
            <div class="title">Add New Game</div>
            <div class="image-and-game-detail-wrapper">
                <div class="image-wrapper">
                    <img src="" class="poster-image">
                    <div class="additional-image-wrapper">
                        <img src="" class="additional-image">
                        <img src="" class="additional-image">
                        <img src="" class="additional-image">
                        <img src="" class="additional-image">
                    </div>
                </div>

                <div class="game-detail-wrapper">
                    <div class="game-detail-title">
                        <div class="title">Game Details</div>
                        <div class="is-online-wrapper">
                            <div class="is-online-title">Online Game: </div>
                            <div class="radio-button-wrapper">
                                <input type="radio" name="is-online-game" value="no" id="is-not-online-game" checked>
                                <label for="is-not-online-game">no</label>
                                <input type="radio" name="is-online-game" value="yes" id="is-online-game">
                                <label for="is-online-game">yes</label>
                            </div>
                        </div>
                    </div>
                    <div class="detail-wrapper">
                        <div class="type">Game Title</div>
                        <div class="value">
                            <input type="text" name="game-title" class="game-title-input" required>
                            <!-- <button><i class="fa-regular fa-pen-to-square"></i> Edit</button> -->
                        </div>
                    </div>
                    <div class="detail-wrapper">
                        <div class="type">Developer</div>
                        <div class="value">
                            <input type="text" name="game-developer" required>
                            <!-- <button><i class="fa-regular fa-pen-to-square"></i> Edit</button> -->
                        </div>
                    </div>
                    <div class="detail-wrapper">
                        <div class="type">Publisher</div>
                        <div class="value">
                            <input type="text" name="game-publisher" required>
                            <!-- <button><i class="fa-regular fa-pen-to-square"></i> Edit</button> -->
                        </div>
                    </div>
                    <div class="detail-wrapper">
                        <div class="type">Download Size (GB)</div>
                        <div class="value">
                            <input type="number" name="download-size" step=".01" required>
                            <!-- <button><i class="fa-regular fa-pen-to-square"></i> Edit</button> -->
                        </div>
                    </div>
                    <div class="detail-wrapper">
                        <div class="type">Youtube Trailer Link</div>
                        <div class="value">
                            <input type="text" name="trailer-link" required>
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
                    <textarea name="image-links" placeholder="First link refers to game poster image link...." class="link-box" spellcheck="false" required></textarea>
                    <!-- <button class="edit-button"><i class="fa-regular fa-pen-to-square"></i> Edit New Links</button> -->
                    <div class="genre-title">Genre</div>
                    <div class="genre-wrapper">
                        <?php
                            for ( $count = 0 ; $count < $totalGenre ; $count++ ) {
                                $genreData = mysqli_fetch_array($genreQueryResult);
                                $genreId = $genreData['id'];
                                $genreType = $genreData['genre_type'];
                            ?>
                                <div class="genre">
                                    <input type="checkbox" name="genre[]" value="<?php echo $genreId;?>" id="genre-id-<?php echo $genreId;?>">
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
                        <div class="value"><input type="text" spellcheck="false" name="spec-min-os" required></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">Processor</div>
                        <div class="value"><input type="text" spellcheck="false" name="spec-min-processor" required></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">Memory</div>
                        <div class="value"><input type="text" spellcheck="false" name="spec-min-memory" required></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">Graphics</div>
                        <div class="value"><input type="text" spellcheck="false" name="spec-min-graphics" required></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">Storage</div>
                        <div class="value"><input type="text" spellcheck="false" name="spec-min-storage" required></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">DirectX</div>
                        <div class="value"><input type="text" spellcheck="false" name="spec-min-directx" required></div>
                    </div>
                </div>

                <div class="spec-wrapper recommended-wrapper">
                    <div class="title">Recommended</div>
                    <div class="sys-info">
                        <div class="type">OS</div>
                        <div class="value"><input type="text" spellcheck="false" name="spec-max-os" required></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">Processor</div>
                        <div class="value"><input type="text" spellcheck="false" name="spec-max-processor" required></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">Memory</div>
                        <div class="value"><input type="text" spellcheck="false" name="spec-max-memory" required></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">Graphics</div>
                        <div class="value"><input type="text" spellcheck="false" name="spec-max-graphics" required></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">Storage</div>
                        <div class="value"><input type="text" spellcheck="false" name="spec-max-storage" required></div>
                    </div>
                    <div class="sys-info">
                        <div class="type">DirectX</div>
                        <div class="value"><input type="text" spellcheck="false" name="spec-max-directx" required></div>
                    </div>
                </div>
            </div>

            <div class="download-link-title">Downloads Links</div>
            <div class="download-link-wrapper">
                <div class="left-wrapper">
                    <div class="link-title">Pixel Drain</div>
                    <br>
                    <textarea name="download-links-1" class="download-link-box pixel-drain-box" placeholder="Please enter only pixeldrain file ids..." spellcheck="false"></textarea>
                </div>
                <div class="right-wrapper">
                    <div class="link-title">Mega Up</div>
                    <br>
                    <textarea name="download-links-2" class="download-link-box mega-up-box" placeholder="Please enter only megaup download links..." spellcheck="false"></textarea>
                </div>
            </div>
            

            <div class="copyright-wrapper">
                Copyright &copy; 2023. All rights reserved by &nbsp;<span>Blacksky PC Game Store</span>.
            </div>
        </form>
    </div>
    
    <script src="scripts/app-script.js"></script>
    <!-- <script src="scripts/game-info-script.js"></script> -->
    <script src="scripts/add-new-game.js"></script>
    <script src="scripts/tab-navigator.js"></script>
    
</body>
</html>