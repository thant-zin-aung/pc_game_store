<?php
    session_start();
    include('admin_panel/db-connect.php');
    include('admin_panel/db-executions.php');
    $isUserLoggedIn = false;
    if ( isset($_SESSION['is-user-logged-in']) ) {
        $isUserLoggedIn = true;
        $loginUsername = $_SESSION['login-username'];
    }
    if ( !$isUserLoggedIn ) header("location: index.php");
    $unitPrice = 1000;
    $availableGenreList = get_available_genre_list($connect);
    $cartItems = get_cart_item_list($connect,$loginUsername);
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
    <link rel="stylesheet" href="styles/cart-style.css">
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
                    <div class="total-game"><?php echo $isUserLoggedIn?count($cartItems):"";?></div>
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
                        <div class="total-game"><?php echo $isUserLoggedIn?count($cartItems):"";?></div>
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

    <div class="cart-wrapper">
        <div class="title">Your Cart Items <span>(<?php echo count($cartItems);?>)</span></div>
        <div class="cart-item-list-wrapper">
            <div class="item-list-wrapper">
                <?php
                    for ( $count = 0 ; $count < count($cartItems) ; $count++ ) {
                        $gameId = $cartItems[$count];
                        $gameData = get_game_info($connect,$gameId);
                        $gameGenreList = get_genre_of_game($connect,$gameId);
                        $gameTitle = $gameData['title'];
                        $posterImage = $gameData['profile_image'];
                        $limitedGenreAmount = count($gameGenreList)>5 ? 5 : count($gameGenreList);
                        ?>
                        <div class="item-wrapper">
                            <div class="left-wrapper">
                                <img src="<?php echo $posterImage;?>">
                            </div>
                            <div class="right-wrapper">
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
                                <div class="game-title"><?php echo $gameTitle;?></div>
                                <div class="remove-button" game-id="<?php echo $gameId;?>"><i class="fa-solid fa-trash-can"></i> Remove</div>
                            </div>
                        </div>
                        <?php
                    }
                ?>
            </div>

            <div class="price-info-wrapper">
                <div class="info-title">Summary</div>
                <div class="info-wrapper">
                    <div class="type">Unit Price</div>
                    <div class="value"><?php echo $unitPrice;?> MMK</div>
                    <input type="hidden" value=<?php echo $unitPrice;?> class="unit-price">
                </div>
                <div class="info-wrapper border-info-wrapper">
                    <div class="type">Subtotal <sup class="sub-total-hint">(<?php echo $unitPrice." x ".count($cartItems);?>)</sup></div>
                    <div class="value sub-total-price"><?php echo $unitPrice*count($cartItems);?> MMK</div>
                </div>
                <div class="info-wrapper">
                    <div class="type">Total</div>
                    <div class="value total-price"><?php echo $unitPrice*count($cartItems);?> MMK</div>
                </div>
                <div class="checkout-button">Check Out</div>
                <div class="continue-shopping-link">
                    <a href="index.php">Continue shopping</a>
                </div>
            </div>
        </div>
    </div>

    <div class="place-order-overlay-wrapper">
        <div class="place-order-wrapper">
            <i class="fa-solid fa-square-xmark close-icon"></i>
            <div class="top-wrapper">
                <div class="receiver-wrapper">
                    <!-- <div class="eng">
                        <div class="title eng">Payment Receiver</div>
                        <div class="name">Name: <span>Thant Zin Aung</span></div>
                        <div class="phone">Phone: <span>09679821063</span></div>
                    </div> -->
                    <div class="mm">
                        <div class="title eng">ငွေလွှဲလက်ခံသူ</div>
                        <div class="sub-label name">အမည်: <span>Thant Zin Aung</span></div>
                        <div class="sub-label phone">ငွေလွှဲလက်ခံဖုန်း: <span>09769952798</span></div>
                    </div>
                    <div class="mm mid-wrapper">
                        <div class="title eng">ဂိမ်းဝယ်ယူသူ</div>
                        <div class="sub-label name">Username: <span><?php echo $loginUsername;?></span></div>
                        <div class="sub-label phone">ဂိမ်းစုစုပေါင်း: <span class="total-game-label"><?php echo count($cartItems);?> games</span></div>
                        <div class="sub-label phone">ကျသင့်ငွေ: <span class="total-price-label"><?php echo count($cartItems)*$unitPrice;?> MMK</span></div>
                    </div>
                    <div class="eng">
                        <div class="title eng">Accepted Payment Methods</div>
                        <div class="payment-wrapper">
                            <div class="payment">
                                <img src="images/payment-logos/kbzpay.png" class="payment-logo">
                                <div class="label">KBZ Pay</div>
                                <input type="radio" class="payment-option kbzpay">
                            </div>
                            <div class="payment">
                                <img src="images/payment-logos/wavepay.png" class="payment-logo">
                                <div class="label">Wave Pay</div>
                                <input type="radio" class="payment-option wavepay">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="payment-proof-wrapper">
                    <div class="screenshot-wrapper">
                        <div class="label">ငွေလွှဲ screenshot ထည့်ပါ</div>
                        <label for="screenshot-input-file" style="background-image: url('');">
                            <i class="fa-solid fa-file-image"></i>
                            <div class="hint-label">Upload screenshot file here.</div>
                        </label>
                        <input type="file" id="screenshot-input-file" class="screenshot-input-file">
                        
                    </div>
                    <div class="or-wrapper">
                        <div class="title">(OR)</div>
                        <div class="title mm">သို့မဟုတ်</div>
                    </div>
                    <div class="transaction-id-wrapper">
                        <label for="transaction-id">ငွေလွဲ transaction id ထည့်ပါ</label>
                        <br>     
                        <input type="text" name="transaction-id" id="transaction-id" class="transaction-id" placeholder="Enter transaction id..." spellcheck="false">
                    </div>
                </div>
            </div>
            <div class="place-order-button">PLACE ORDER</div>
            <div class="remark-wrapper">
                <div class="title">
                    မှတ်ချက်
                </div>
                <div class="description">
                    မိမိဝယ်ယူသည့်ဂိမ်းအရေအတွက်အတိုင်းကျသင့်ငွေအား ငွေလွှဲလက်ခံသူဆီသို့ ငွေလွဲပြီးပါက "<span>Transaction id ပါသောငွေလွဲစလစ်အား screenshot ရိုက်ကာ upload တင်ပြီးပေးပို့ခြင်း</span>" သို့မဟုတ် "<span>ငွေလွဲစလစ်တွင်ပါသော transaction id အား manual ရိုက်ထည့်ပေးခြင်း</span>" ဖြင့် order တင်ကာဝယ်ယူနိုင်ပါသည်။
                    Order တင်ပြီး 5 နာရီအတွင်း (8 AM - 10 PM) Admin မှငွေလွဲရောက်မရောက်စစ်ဆေးပြီး မှန်ကန်ပါက approve လုပ်ပေးမည်ဖြစ်သည်။ Admin မှ approve လုပ်ပြီးသည်နှင့် မိမိဝယ်ယူထားသော Game များအား မိမိ account ရှိ My game library တွင်ဝင်ရောက်ကာစတင်အသုံးပြုနိုင်ပြီဖြစ်သည်။
                    ငွေလွဲမရောက်ခြင်း သို့မဟုတ် ကျသင့်သည့်ငွေပမာဏအတိုင်းလွှဲထားခြင်းမရှိပါက Order ကို decline လုပ်မည်ဖြစ်ပါသည်။ မှန်ကန်သော refund process များအတွက် Blacksky PC Game Store facebook page အားဆက်သွယ်စုံစမ်းနိုင်ပါသည်။
                </div>
            </div>
        </div>
    </div>


    <div class="order-confirm-overlay-wrapper">
        <div class="order-confirm-wrapper">
            <i class="fa-regular fa-circle-check confirm-icon"></i>
            <div class="ack-label user-select-none">Hey <?php echo $loginUsername;?>,</div>
            <div class="confirm-message user-select-none">Your Order is Confirmed!</div>
            <div class="remark-message user-select-none">Please wait for the admin approval to access your games. Waiting time will be within next 5 hours (8 AM - 10 PM)</div>
            <div class="home-button user-select-none">Back To Home</div>
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
    <script src="scripts/cart-script.js"></script>
</body>
</html>