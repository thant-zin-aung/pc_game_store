<?php
    session_start();
    include('admin_panel/db-connect.php');
    include('admin_panel/db-executions.php');
    $isUserLoggedIn = false;
    $browseGameLimit = 1;
    if ( isset($_SESSION['is-user-logged-in']) ) {
        $isUserLoggedIn = true;
        $loginUsername = $_SESSION['login-username'];
        $cartItemList = get_cart_item_list($connect,$loginUsername);
    }
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
    $browseGameStartingPoint = ($currentPaginationNumber==1) ? 6 : $browseGameLimit*($currentPaginationNumber-1)+6;
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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Megrim&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/e2c9faac31.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="styles/app-style.css">
    <link rel="stylesheet" href="styles/download-guide.css">
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
        <div class="ad-blocker-wrapper">
            <div class="description">
                Game များအား download မပြုလုပ်မီ မိမိ pc တွင်ရှိသော browser သို့ ad blocker extension တခုအားအရင်ထည့်သွင်းပေးထားလျှင်ပိုအဆင်ပြေပါသည်။ Ad blocker extension အား browser တွင်ထည့်သွင်းရန်အတွက်အောက်တွင်ဖော်ပြထားသော
                "Go To Ad Blocker Page" button အား click လုပ်ပေးပါ။ ထို့နောက် ပုံတွင်ဖော်ပြထားသည့် Add to chrome ဆိုသည့် button လေးအား click ထပ်လုပ်ပေးပါ။ ထို့နောက် Add extension ဆိုသည့် button လေးအား click လုပ်ပြီးပါက
                browser မှ အလိုအလျောက် extension အား download ဆွဲပြီးထည့်သွင်းပေးသွားမည်ဖြစ်သည်။
            </div>
            <a class="go-to-ad-blocker-button" href="https://chromewebstore.google.com/detail/adguard-adblocker/bgnkhhnnamicmpeenaelnjfhikgbkllg" target="_blank">Go To Ad Blocker Page</a>
            <div class="ad-blocker-image-wrapper">
                <img src="images/download-guide-images/ad-guard.png">
            </div>
        </div>
        <div class="ad-blocker-wrapper">
            <div class="description">
                အထက်ပါအဆင့်ပြီးသွားပါက game များအား download စတင်ဆွဲနိုင်ပြီဖြစ်ပါသည်။ Game များတွင် download ဆွဲရန်အတွက် part များဖြင့်ခွဲပေးထားပါသည်။ Game ကိုစက်တွင်အောင်မြင်စွာ install ပြုလုပ်နိုင်ရန်အတွက် game တွင်ပါရှိသော part များအားလုံးကို
                 download ဆွဲပေးရန်လိုအပ်ပါသည်။ part များအား download ဆွဲရန်အတွက် မိမိဝယ်ယူထားသော Game ဧ။် detail သို့ဝင်ကာ part တခုချင်းစီ download ပြုလုပ်ပေးရမည်ဖြစ်ပါသည်။ အောက်ပါပုံများကိုကြည့်ကာ လုပ်ဆောင်နိုင်ပါသည်။
            </div>
            <div class="game-download-screenshot-wrapper">
                <img src="images/download-guide-images/game-download-screenshot-1.png">
                <img src="images/download-guide-images/game-download-screenshot-2.png">
                <img src="images/download-guide-images/game-download-screenshot-3.png">
            </div>
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