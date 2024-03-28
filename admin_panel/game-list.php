<?php
    session_start();
    include('db-connect.php');
    include('db-executions.php');
    if ( isset($_SESSION['is_admin_logged_in']) ) {
        $adminUsername = $_SESSION['admin_username'];
    } else {
        header("location: login.php");
    }
    $totalGameCount = get_total_game($connect);
    $totalOnlineGame = get_total_online_or_offline_game($connect,true);
    $totalOfflineGane = get_total_online_or_offline_game($connect,false);
    $totalNewOrder = get_total_new_orders_count($connect);
    $displayGameLimit = 65;

    if ( isset($_POST['add-genre-button']) ) {
        $genreType = $_POST['genre-type'];
        $genreQuery = "INSERT INTO genre_list(genre_type) VALUE('$genreType')";
        $genreQueryResult = mysqli_query($connect,$genreQuery);
        if ( $genreQueryResult ) {
            echo "<script>alert('New genre has been added successfully...');</script>";
        } else {
            echo "<script>alert('Warning!!! Failed to add new genre...');</script>";
        }
    }

    // for displaying genre list...
    $genreQuery = "SELECT * FROM genre_list";
    $genreQueryResult = mysqli_query($connect,$genreQuery);
    $totalGenre = mysqli_num_rows($genreQueryResult);
    // for displaying genre list...
        
    if ( isset($_GET['search-button']) ) {
        $gameTitle = $_GET['game-title'];
        $gameQuery = "SELECT * FROM game_list WHERE title LIKE '%%$gameTitle%%' ORDER BY id DESC";
    } else {
        $gameQuery = "SELECT * FROM game_list ORDER BY id DESC LIMIT $displayGameLimit";    
    }

    // for displaying game list...
    $gameQueryResult = mysqli_query($connect,$gameQuery);
    $totalGame = mysqli_num_rows($gameQueryResult);
    // for displaying game list...

    // if user click on detail button...
    if ( isset($_POST['detail-button']) ) {
        $gameId = $_POST['game-id'];
        echo "<script>window.location = 'game-info.php?game-id=$gameId'</script>";
    }
    // if user click on detail button...
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
    <link rel="stylesheet" href="styles/game-list-style.css">
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

    <form class="add-genre-wrapper" action="game-list.php" method="POST">
        <input type="text" class="genre-type-box" spellcheck="false" placeholder="Enter genre type..." name="genre-type" required>
        <button type="submit" class="button add-genre-button" name="add-genre-button"><i class="fa-solid fa-circle-plus" aria-hidden="true"></i> &nbsp; Add Genre</button>
        <div class="button close-button"><i class="fa-solid fa-circle-xmark"></i> &nbsp; CLOSE</div>
    </form>

    <div class="main-wrapper">
        <div class="title">PC Game List</div>
        <div class="game-detail-wrapper">
            <div class="game-username">blacksky00797</div>
            <div class="game-id"># Admin</div>
            <div class="add-new-game-button"><i class="fa-solid fa-plus"></i> Add New Game</div>
            <div class="detail-wrapper">
                <div class="detail">
                    <div class="type">Total Game</div>
                    <div class="value"><?php echo $totalGameCount;?> Games</div>
                </div>
                <div class="detail">
                    <div class="type">Online Games</div>
                    <div class="value"><?php echo $totalOnlineGame;?> Games</div>
                </div>
                <div class="detail">
                    <div class="type">Offline Games</div>
                    <div class="value"><?php echo $totalOfflineGane;?> Games</div>
                </div>
            </div>
            <div class="detail-wrapper">
                <div class="detail">
                    <div class="type">Available Genre (12) 
                        <div class="add-genre-button"><i class="fa-solid fa-circle-plus" aria-hidden="true"></i> Add Genre</div>
                    </div>
                    <div class="genre-wrapper">
                        <?php
                            for ( $count = 0 ; $count < $totalGenre ; $count++ ) {
                                $genreData = mysqli_fetch_array($genreQueryResult);
                            ?>
                                <div class="genre"><?php echo $genreData['genre_type'];?></div>
                            <?php
                            }
                        ?>
                        
                    </div>
                </div>
            </div>
            



            <div class="login-history-title">
                <div class="title">
                    <i class="fa-solid fa-list-check"></i> Available Game List (<?php echo $totalGame;?>)
                </div>
                <form class="search-wrapper" action="game-list.php" method="GET">
                    <input type="text" placeholder="Enter game title..." name="game-title">
                    <input type="submit" value="Search" name="search-button">
                </form>
            </div>
            <div class="game-list-wrapper">
                <?php
                    for ( $count = 0 ; $count < $totalGame ; $count++ ) {
                        $gameData = mysqli_fetch_array($gameQueryResult);
                        $gameId = $gameData['id'];
                        $gameTitle = $gameData['title'];
                        $gamePoster = $gameData['profile_image'];
                        ?>

                        <form class="game-wrapper" action="game-list.php" method="POST">
                            <img src="<?php echo $gamePoster;?>">
                            <div class="button-wrapper">
                                <input type="hidden" name="game-id" value="<?php echo $gameId;?>">
                                <button type="submit" name="detail-button" class="button detail-button">Details</button>
                                <button type="submit" name="delete-button" class="button delete-button">Delete</button>
                            </div>
                            <div class="game-title"><?php echo $gameTitle;?></div>
                        </form>

                        <?php
                    }
                ?>
                <!-- <form class="game-wrapper" action="game-list.php" method="POST">
                    <img src="https://i.ibb.co/tKVG48D/Far-Cry-6-Logo.jpg">
                    <div class="button-wrapper">
                        <input type="hidden" name="game-id" value="">
                        <button type="submit" name="detail-button" class="button detail-button">Details</button>
                        <button type="submit" name="delete-button" class="button delete-button">Delete</button>
                    </div>
                    <div class="game-title">Far Cry 6</div>
                </form>
                <div class="game-wrapper">
                    <img src="https://i.ibb.co/MpKQZY2/Lego-Star-Wars-The-Sky-Walker-Saga-Logo.jpg">
                    <div class="button-wrapper">
                        <div class="button detail-button">Details</div>
                        <div class="button delete-button">DELETE</div>
                    </div>
                    <p class="game-title">LEGO Star Wars: The Skywalker Saga</p>
                </div> -->
                
                
            </div>
        </div>
        

        <div class="copyright-wrapper">
            Copyright &copy; 2023. All rights reserved by &nbsp;<span>Blacksky PC Game Store</span>.
        </div>
    </div>
    
    <script src="scripts/app-script.js"></script>
    <!-- <script src="scripts/order-detail-script.js"></script> -->
    <script src="scripts/game-list-script.js"></script>
    <script src="scripts/tab-navigator.js"></script>
</body>
</html>